<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ page.get('title') ? locale._(page.get('title')) ~ ' - '  : '' }}{{ site.get('app-name' , getenv('APP_NAME') ? getenv('APP_NAME') :  'Sakura Panel') }}</title>

  {{ assets.outputCss('header') }}
  {% if dataTable is defined %}{{ assets.outputCss('dataTable') }}{% endif %}
  {{ assets.outputCss('customcss') }}


  <base href="{{ url() }}">
</head>
<body id="page-top" class="{{ page.get('body.class') }} <?=(@$_COOKIE['sidebar-collapse'] === 'hide') ? 'sidebar-toggled':'' ?>"">