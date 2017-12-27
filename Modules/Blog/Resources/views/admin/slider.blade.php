@extends('blog::layouts.master')

@section('content')
<div class="col-md-12 admin-row-child">
<h4>Slider</h4>
<a href="{{ route('panel.slider__add') }}" class="btn btn-round btn-fill btn-info"> New Slider + </a>

<div class="card" style="margin-top: 10px;">
    <div class="card-content table-responsive">
        <table class="table" id="myTableslider">
            <thead >
                <th width="80px">Preview</th>
                <th>Title</th>
            	<th>Created At</th>
				<th width="80px">Action</th>
            </thead>
            <tbody>
            @foreach($sliders as $slider)
            <tr>
                <td><img style="width: 100px; max-height: 100px;" src="{{ asset($slider->image) }}"></td>
                <td>{{ strip_tags($slider -> title) }}</td>
                <td>{{ $slider -> created_at }}</td>
                <td><a href="{{ route('panel.slider__view', $slider->id) }}">Edit</a> | <a onclick="return confirm('Yakin menghapus media ini?');" href="{{ route('panel.slider__delete', $slider->id) }}">Hapus</a></td>
            </tr>
            @endforeach
            </tbody>
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
