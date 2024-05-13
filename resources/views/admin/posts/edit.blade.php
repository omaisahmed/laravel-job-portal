@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('front.message')

                    <form action="{{ route('admin.posts.update', $post->id) }}" method="post" id="editPostForm"
                        name="editPostForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card border-0 shadow mb-4 ">
                            <div class="card-body card-form p-4">
                                <h3 class="fs-4 mb-1">Edit Post</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Title<span class="req">*</span></label>
                                        <input type="text" value="{{ $post->title }}" placeholder="Job Title"
                                            id="title" name="title" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Sub Title<span class="req">*</span></label>
                                        <input type="text" value="{{ $post->subtitle }}" placeholder="Sub Title"
                                            id="subtitle" name="subtitle" class="form-control">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Category<span class="req">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="">Select a Category</option>
                                            @if ($categories->isNotEmpty())
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Slug<span class="req">*</span></label>
                                        <input type="text" value="{{ $post->slug }}" placeholder="Slug" id="slug"
                                            name="slug" class="form-control">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Image<span class="req">*</span></label>
                                        <input type="file" name="image" id="image" class="form-control"
                                            placeholder="Image">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Status<span class="req">*</span></label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Select status</option>
                                            <option value="active" {{ $post->status === 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive" {{ $post->status === 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-4 col-md-12">
                                        <label for="" class="mb-2">Write Post Here<span
                                                class="req">*</span></label>
                                        <textarea class="textarea" name="body" id="body" cols="5" rows="5" placeholder="Write Post Here">{{ $post->body }}</textarea>
                                        <p></p>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer p-4">
                                <button type="submit" class="btn btn-primary">Update Post</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        $("#editPostForm").submit(function(e) {
            e.preventDefault();
            $("button[type='submit']").prop('disabled', true);
            var formData = new FormData(this);
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);

                    if (response.status == true) {

                        $("#title").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#category_id").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#subtitle").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#image").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#body").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        window.location.href = "{{ route('admin.posts.index') }}";

                    } else {
                        var errors = response.errors;

                        if (errors.title) {
                            $("#title").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.title)
                        } else {
                            $("#title").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.status) {
                            $("#status").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.status)
                        } else {
                            $("#status").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.category_id) {
                            $("#category_id").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.category_id)
                        } else {
                            $("#category_id").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.subtitle) {
                            $("#subtitle").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.subtitle)
                        } else {
                            $("#subtitle").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.slug) {
                            $("#slug").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.slug)
                        } else {
                            $("#slug").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.image) {
                            $("#image").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.image)
                        } else {
                            $("#image").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if (errors.body) {
                            $("#body").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.body)
                        } else {
                            $("#body").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                    }

                }
            });
        });
        // function deleteUser(id) {
        //     if(confirm("Are you sure you want to delete?")) {
        //         $.ajax({
        //             url: '{{ route('admin.users.destroy') }}',
        //             type: 'delete',
        //             data: { id: id},
        //             dataType: 'json',
        //             success: function(response) {
        //                 window.location.href = "{{ route('admin.users') }}";
        //             }
        //         });
        //     }
        // }
    </script>
@endsection
