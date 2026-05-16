@extends('layouts.admin')

@section('title')
    <title>Content List</title>
    
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <iframe src="/media/filemanager/index.php" style="width: 100%; height: 100vh; border: none;"></iframe>
        </div>
    </div>
@endsection