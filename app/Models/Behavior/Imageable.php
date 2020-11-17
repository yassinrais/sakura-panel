<?php

namespace Sakura\Models\Behavior;

use Phalcon\Logger;
use Phalcon\Mvc\Model\Behavior;
use Phalcon\Mvc\Model\BehaviorInterface;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\Exception;
use Symfony\Component\Filesystem\Filesystem; // Not necessarily, just convenient

class Imageable extends Behavior implements BehaviorInterface
{
    /**
     * Upload image path
     * @var string
     */
    protected $uploadPath = null;

    /**
     * Upload image path
     * @var string
     */
    protected $uploadDir = null;

    /**
     * Model field
     * @var null
     */
    protected $imageField = null;

    
    /**
     * File Input field
     * @var null
     */
    protected $inputName = null;

    /**
     * Old model image
     * @var string
     */
    protected $oldFile = null;

    /**
     * Application logger
     * @var \Phalcon\Logger\Adapter\File
     */
    protected $logger = null;

    /**
     * Filesystem Utils
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem = null;

    /**
     * Allowed types
     * @var array
     */
    protected $allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];

    public function notify($eventType, ModelInterface $model)
    {
        if (!is_string($eventType)) {
            throw new Exception('Invalid parameter type.');
        }

        // Check if the developer decided to take action here
        if (!$this->mustTakeAction($eventType)) {
            return;
        }

        $options = $this->getOptions($eventType);

        if (is_array($options)) {
            $this->logger = $model->getDI()->get('logger');
            $this->filesystem = new Filesystem;

            $this->setImageField($options, $model)
                 ->setAllowedFormats($options)
                 ->setUploadPath($options)
                 ->processUpload($model);
        }
    }

    protected function setImageField(array $options,  ModelInterface $model)
    {
        if (!isset($options['field']) || !is_string($options['field'])) {
            throw new Exception("The option 'field' is required and it must be string.");
        }

        $this->inputName = $options['inputName'] ?: 'file';
        $this->imageField = $options['field'];
        $this->oldFile = $model->{$this->imageField};

        return $this;
    }

    protected function setAllowedFormats(array $options)
    {
        if (isset($options['allowedFormats']) && is_array($options['allowedFormats'])) {
            $this->allowedFormats = $options['allowedFormats'];
        }

        return $this;
    }

    // Symfony\Component\Filesystem\Filesystem uses here, you can do it otherwise
    protected function setUploadPath(array $options)
    {
        if (!isset($options['uploadDir']) || !is_string($options['uploadDir'])) {
            throw new Exception("The option 'uploadDir' is required and it must be string.");
        }

        $dir = $options['uploadDir'];
        $path = $options['uploadPath'];

        if (!$this->filesystem->exists($dir)) {
            $this->filesystem->mkdir($dir);
        }

        $this->uploadDir = $dir;
        $this->uploadPath = $path;

        return $this;
    }

    protected function processUpload(ModelInterface $model)
    {
        /** @var \Phalcon\Http\Request $request */
        $request = $model->getDI()->getRequest();

        foreach ($request->getUploadedFiles() as $file) {
            // NOTE!!!
            // Nothing was validated here! Any validations must be are made in a appropriate validator

            if ($file->getKey() != $this->inputName || !in_array($file->getType(), $this->allowedFormats)) {
                continue;
            }

            $uniqueFileName = time() . '-' . uniqid() . '.' . strtolower($file->getExtension());

            if ($file->moveTo(rtrim($this->uploadDir, '/\\') . DIRECTORY_SEPARATOR . $uniqueFileName)) {
                $model->writeAttribute($this->imageField, preg_replace('#/+#','/', $this->uploadPath .'/'. $uniqueFileName ) );

                $this->logger->log(Logger::INFO, sprintf(
                    'Success upload file %s into %s : path %s', $uniqueFileName, $this->uploadDir, $this->uploadPath
                ));

                // Delete old file
                $this->processDelete();
            }
        }
        return $this;
    }

    // Symfony\Component\Filesystem\Filesystem uses here, you can do it otherwise
    protected function processDelete()
    {
        if ($this->oldFile) {
            if (!is_file($this->oldFile))
                return;
                
            $fullPath = $this->oldFile;

            try {
                $this->filesystem->remove($fullPath);
                $this->logger->log(Logger::INFO, sprintf('File %s deleted successful.', $fullPath));
            } catch(\Exception $e) {
                $this->logger->log(Logger::ALERT, sprintf(
                    'An error occurred deleting file %s: %s', $fullPath, $e->getMessage()
                ));
            }
        }
    }
}