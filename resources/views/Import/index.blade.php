@extends('layout.master')
@section('title','資料控制')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="text-center text-dark my-3">匯入資料</div>
            <form id="importMediaBiasFactCheckForm" name="importMediaBiasFactCheckForm" method="post" action="{{route('import.ADFontesMedia')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-floating my-3">
                    <input type="email" class="form-control" id="message_tip" placeholder="狀態提示" value="" disabled>
                    <label for="floatingInput">請先選擇檔案後匯入 第一個API ADFontesMedia</label>
                </div>
                <div class="mb-3">
                    <input class="form-control" type="file" id="formFile" value="" name="api_ad_fontes_media" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit">送出</button>
                </div>
                <div class="d-grid gap-2 my-2">
                    <button class="btn btn-danger" type="reset">清空</button>
                </div>
            </form>
            <form id="importMediaBiasFactCheckForm" name="importMediaBiasFactCheckForm" method="post" action="{{route('import.MediaBiasFactCheck')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-floating my-3">
                    <input type="email" class="form-control" id="message_tip" placeholder="狀態提示" value="" disabled>
                    <label for="floatingInput">請先選擇檔案後匯入 第二個API MediaBiasFactCheck</label>
                </div>
                <div class="mb-3">
                    <input class="form-control" type="file" id="formFile" value="" name="api_media_bias_fact_check" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                <div class="d-grid gap-2 my-2">
                    <button class="btn btn-primary" type="submit">送出</button>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-danger" type="reset">清空</button>
                </div>
            </form>
            <form id="importMediaBiasFactCheckForm" name="importMediaBiasFactCheckForm" method="post" action="{{route('import.MediaBiasFactCheckJson')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-floating my-3">
                    <input type="email" class="form-control" id="message_tip" placeholder="狀態提示" value="" disabled>
                    <label for="floatingInput">請先選擇檔案後匯入 第二個API MediaBiasFactCheck.json</label>
                </div>
                <div class="mb-3">
                    <input class="form-control" type="file" id="formFile" value="" name="api_media_bias_fact_json_check" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </div>
                <div class="d-grid gap-2 my-2">
                    <button class="btn btn-primary" type="submit">送出dasda</button>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-danger" type="reset">清空</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('js/importApiData/importApiData.js?v='.time())}}" type="text/javascript"></script>
@endsection
