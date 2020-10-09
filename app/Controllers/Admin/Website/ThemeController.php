<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Admin\Website;

use SakuraPanel\Controllers\Member\MemberControllerBase;
use SakuraPanel\Library\Datatables\DataTable;
use SakuraPanel\Forms\Website\ThemeFileForm;

/**
 * ThemeController
 */
class ThemeController extends MemberControllerBase
{

    // Implement common logic
    public function initialize(){
    	parent::initialize();

        $this->page->set('title','Theme Settings');
        $this->page->set('base_route', 'admin/website-theme');
        $this->page->set('description','Add Custom Style / Scripts to Panel .');
        $this->assetsPack->header->addCss('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.57.0/codemirror.min.css' ,false);
        $this->assetsPack->footer->addJs('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.57.0/codemirror.min.js' ,false)
                                 ->addJs('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.57.0/mode/css/css.min.js',false);
	
    }
	public function indexAction()
	{
        $this->view->dataTable = true;

		return $this->view->pick('admin/website/theme');
	}



    public function ajaxAction()
    {
        if ($this->request->isAjax() || getenv('DEV_MODE') == "true" ) {
            $dataTables = new DataTable();
  
            $dataTables->setOptions([
                'limit'=> abs((int) $this->request->get('length'))
            ]);
            $dataTables->setIngoreUpperCase(true);
    
            $dataTables->fromArray(call_user_func(function(){
                $data = [];
                $files = (object) $this->getThemeFiles();
                foreach($files as $i => $f){
                    $f = (object) $f;
                    $data[]=[
                        "name"=> "<b>{$f->name}</b><br><small>{$f->path}</small>",
                        "type"=> "<b>{$f->type}</b> : {$f->mtype}",
                        "size"=> "<b>{$f->size}</b>",
                        "link"=> urlencode($f->path ?? "")
                    ];
                }

                return $data;
            }) );
  
            $dataTables->addCustomColumn('c_actions' , function ($key , $data)
            {
                return  "<span  onclick='window.location.href=\"admin/website-theme/edit/file?f=".urlencode($data['link'])."\"' 
                         title='Edit'  class='ml-1 btn btn-info btn-circle btn-sm '><i class='fas fa-pencil-alt'></i></span>
                <span title='Delete' data-action ='delete' data-id='".urldecode($data['link'])."' class='ml-1 btn btn-danger btn-circle btn-sm table-action-btn'><i class='fas fa-trash'></i></span>";
            });
  
                return $dataTables->sendResponse();
        }else{
            return $this->ajax->disableArray()->error('Only Ajax Method is alowed ! ')->sendResponse();
        }
    }


    public function editAction(string $x = null)
    {
        $file = (urldecode($this->request->get('f') ?: ""));

        if (!$this->getThemeFiles()[$file]){

            $this->flashSession->error('This file is not allowed to edit !');
            return $this->response->redirect($this->page->get('base_route'));
        }
        
        $file_content = file_get_contents($file);
        $file_extension = pathinfo($file,PATHINFO_EXTENSION);
        $file_name = pathinfo($file , PATHINFO_FILENAME);

        // view file vars
        $this->view->file_name = $file_name;
        $this->view->file_content = $file_content;
        $this->view->file_extension = $file_extension;
        $this->view->file_type = strpos("css", $file_extension) >= 0 ? "css":"javascript";

        // action posted
        if (!empty($this->request->has('action'))){
            $tmp_file = $file.'.tmp';
        
            if ($this->request->getPost('action') == "save"){
                
                $new_content = $this->request->getPost('content');
                
                if (is_file($tmp_file))
                    unlink($tmp_file);

                try{
                    file_put_contents($tmp_file , $file_content);
                    file_put_contents($file , $new_content);

                    $this->flashSession->success('File content saved successfully !');

                }catch(Exception $e){
                    $this->flashSession->error('File Save Failed ! '. $e->getMessage());
                }
            }elseif($this->request->getPost('action') == "restore"){
                if (is_file($tmp_file)){
                    $content = file_get_contents($tmp_file);

                    try{
                        file_put_contents($file , $content);
                        unlink($tmp_file);
    
                        $this->flashSession->success('File content restored successfully !');
    
                    }catch(Exception $e){
                        $this->flashSession->error('File restore Failed ! '. $e->getMessage());
                    }
                }else{
                    $this->flashSession->warning('The restore copy file was not found !');
                }
            }

            return $this->response->redirect($this->request->getHTTPReferer());
        }
        
        return $this->view->pick('admin/website/editFile');
    }

    
    /** 
     * Delete Action
     * file will be deleted if is a custom one 
     * else deletion is denied ! 
     */
    public function deleteAction()
    {
        $this->ajax->disableArray();
        $file = (urldecode($this->request->get('id') ?: ""));

        if (!$this->getThemeFiles()[$file])
            return $this->ajax->error('This file is not allowed to delete !')->sendResponse();


        try{
            unlink($file);

            $this->ajax->success('File Deleted successfully ! ');
        }catch(Exception $e){
            $this->ajax->error((string) $e->getMessage());
        }

        return $this->ajax->sendResponse();
    }


    public function createAction()
    {
        $form = new ThemeFileForm();

        if ($this->request->has('action')){
            $path = $this->getCustomFilesPath();
            $data = $this->request->getPost();

            if ($form->isValid($data)){
                $fileName = $data["name"] . "." . $data['type'];
                $filePath = $path . $fileName ;

                if (is_file($filePath)){
                    $this->flashSession->error("File `$fileName` already exists ! ");
                }else{
                    // create file

                    try{
                        file_put_contents($filePath , null);

                        $this->flashSession->success("File `$fileName` successfully created ! ");
                    }catch(Exception $e){
                        $this->flashSession->error('Error ! ' . (string) $e->getMessage());
                    }
                }
                
            }else
                foreach($form->getMessages() as $x => $msg)
                    $this->flashSession->error((string) $msg);

        }

        $this->view->form = $form;
        $this->view->pick('admin/website/createFile');
    }
}