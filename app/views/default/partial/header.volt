<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ page.get('title') ? page.get('title') ~ ' - '  : '' }}{{ site.get('name' , getenv('APP_NAME') ? getenv('APP_NAME') :  'Sakura Panel') }}</title>

  {{ assets.outputCss('header') }}
</head>
<body class="{{ page.get('body.class') }}">