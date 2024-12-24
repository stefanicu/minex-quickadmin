@extends('layouts.frontend')
@section('content')
    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                @if($application)
                    <li class="breadcrumb-item"><a
                                href="{{ route('categories.'.app()->getLocale(), ['app_slug' =>$application->slug]) }}">{{ $application->name }}</a>
                    </li>
                @endif
                @if($category)
                    <li class="breadcrumb-item"><a
                                href="{{ route('products.'.app()->getLocale(), ['app_slug' =>$application->slug, 'cat_slug' => $category->slug]) }}">{{ $category->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>


        @php
            if($category && $category->getCoverPhotoAttribute()){
                $cover_image_url = $category->getCoverPhotoAttribute()->getUrl();
            }else{
                $cover_image_url = asset('/img/headers/aplicatie-xl.jpg');
            }
        @endphp

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" media="(min-width: 1200px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-lg.jpg') }}" media="(min-width: 992px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-md.jpg') }}" media="(min-width: 576px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-sm.jpg') }}" media="(max-width: 576px)">--}}
                <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ $cover_image_url }}"
                     alt="{{ trans('pages.product') }}" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ $cover_image_url }}" alt="{{ trans('pages.product') }}">
                </noscript>
            </picture>
        </figure>
    </div>
    <div class="container pb-4">
        <div class="row">
            <div class="col-12 col-md-8">
                <h1 class="h2">{{ $product->name }}</h1>
                <hr>
                @if(!$brand || $brand->online === 1)
                    {!! $product->description !!}
                @else
                    {!! $brandOfflineMessage !!}
                @endif
            </div>
            <div class="col-12 col-md-4">
                @if($product->getMainPhotoAttribute())
                    <div class="mx-auto">
                        <div class="bxslider-img mx-auto">
                            <div>
                                <figure class="mx-auto">
                                    <img srcset="{{ $product->getMainPhotoAttribute()->getUrl() }}"
                                         class="mx-auto lozad img-fluid" alt="No Image">
                                </figure>
                            </div>

                            @if($product->getPhotoAttribute() !== null && $product->getPhotoAttribute()->count()>0)
                                @foreach ($product->getPhotoAttribute()->all() as $image)
                                    <div>
                                        <figure class="mx-auto">
                                            <img srcset="{{ $image->getUrl() }}" class="mx-auto lozad img-fluid"
                                                 alt="{{ $product->name }} {{ $loop->iteration }}">
                                        </figure>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container pt-4">
        <div class="row">
            <div class="col-12 col-md-8">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        @if($product->specifications)
                            <a class="nav-item nav-link active show" id="nav-{{ trans('pages.specifications') }}-tab"
                               data-toggle="tab" href="#nav-{{ trans('pages.specifications') }}" role="tab"
                               aria-controls="nav-{{ trans('pages.specifications') }}" aria-selected="false">
                                {{ trans('pages.specifications') }}
                            </a>
                        @endif

                        @if($product->advantages)
                            <a class="nav-item nav-link" id="nav-{{ trans('pages.advantages') }}-tab" data-toggle="tab"
                               href="#nav-{{ trans('pages.advantages') }}" role="tab"
                               aria-controls="nav-{{ trans('pages.advantages') }}" aria-selected="false">
                                {{ trans('pages.advantages') }}
                            </a>
                        @endif

                        @if($product->usages)
                            <a class="nav-item nav-link" id="nav-{{ trans('pages.usages') }}-tab" data-toggle="tab"
                               href="#nav-{{ trans('pages.usages') }}" role="tab"
                               aria-controls="nav-{{ trans('pages.usages') }}" aria-selected="false">
                                {{ trans('pages.usages') }}
                            </a>
                        @endif

                        @if($product->accessories)
                            <a class="nav-item nav-link" id="nav-{{ trans('pages.accessories') }}-tab" data-toggle="tab"
                               href="#nav-{{ trans('pages.accessories') }}" role="tab"
                               aria-controls="nav-{{ trans('pages.accessories') }}" aria-selected="false">
                                {{ trans('pages.accessories') }}
                            </a>
                        @endif

                        @if($references && $references->count()>0)
                            <a class="nav-item nav-link" id="nav-{{ trans('pages.references') }}-tab" data-toggle="tab"
                               href="#nav-{{ trans('pages.references') }}" role="tab"
                               aria-controls="nav-{{ trans('pages.references') }}" aria-selected="false">
                                {{ trans('pages.references') }}
                            </a>
                        @endif
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">

                    @if($product->specifications)
                        <div class="tab-pane fade show active p-4" id="nav-{{ trans('pages.specifications') }}"
                             role="tabpanel" aria-labelledby="nav-{{ trans('pages.specifications') }}-tab">
                            <div class="table-responsive">
                                {!! $product->specifications !!}
                            </div>
                        </div>
                    @endif

                    @if($product->advantages)
                        <div class="tab-pane fade p-4" id="nav-{{ trans('pages.advantages') }}" role="tabpanel"
                             aria-labelledby="nav-{{ trans('pages.advantages') }}-tab">
                            {!! $product->advantages !!}
                        </div>
                    @endif

                    @if($product->usages)
                        <div class="tab-pane fade p-4" id="nav-{{ trans('pages.usages') }}" role="tabpanel"
                             aria-labelledby="nav-{{ trans('pages.usages') }}-tab">
                            {!! $product->usages !!}
                        </div>
                    @endif

                    @if($product->accessories)
                        <div class="tab-pane fade p-4" id="nav-{{ trans('pages.accessories') }}" role="tabpanel"
                             aria-labelledby="nav-{{ trans('pages.accessories') }}-tab">
                            {!! $product->accessories !!}
                        </div>
                    @endif

                    @if($references && $references->count()>0)
                        <div class="tab-pane fade p-4" id="nav-{{ trans('pages.references') }}" role="tabpanel"
                             aria-labelledby="nav-{{ trans('pages.references') }}-tab">
                            <ul>
                                @foreach($references as $reference)
                                    <li>
                                        <a href="{{ url('') }}/{{ trans('pages_slugs.reference') }}/{{ $reference->slug }}">{{ $reference->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>


                <div class="container mt-5">
                    <!-- Tab Buttons -->
                    <div class="nav nav-tabs" role="tablist">
                        <button class="nav-link active" id="tab1-btn" type="button" role="tab" aria-controls="tab1"
                                aria-selected="true">
                            Tab 1
                        </button>
                        <button class="nav-link" id="tab2-btn" type="button" role="tab" aria-controls="tab2"
                                aria-selected="false">
                            Tab 2
                        </button>
                        <button class="nav-link" id="tab3-btn" type="button" role="tab" aria-controls="tab3"
                                aria-selected="false">
                            Tab 3
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-btn">
                            <p>This is the content for Tab 1.</p>
                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-btn">
                            <p>This is the content for Tab 2.</p>
                        </div>
                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-btn">
                            <p>This is the content for Tab 3.</p>
                        </div>
                    </div>
                </div>

                <ul>
                    @if($files)
                        @foreach($files as $file)
                            <li><a href='{{ url('') }}/uploads/files/{{ $file->name }}'>{{ $file->title }}</a></li>
                        @endforeach
                    @endif
                </ul>

                <p class="text-center">{{ trans('pages.ask_our_experts') }}</p>
                <div class="panel panel-default">
                    <a href="tel:{{ trans('pages.phone') }}" class="btn btn-primary" style="width:100%" id="form">
                        {{ trans('pages.call_us') }} {{ trans('pages.phone') }}
                    </a>
                </div>


                @if(session('success'))
                    <div id="contact" class="multumim-center">{{ trans('frontend.contact_thank_you') }}</div>
                @else
                    <div class="card my-4">
                        <div class="card-header">
                            <h4 class="panel-title">{{ trans('pages.contact_form') }}</h4>
                        </div>
                        <div class="card-body">
                            <form class="px-0 px-md-4" id="detaliiprodus" autocomplete="off"
                                  action="{{ url('') }}/contact/" method="post">
                                @csrf
                                @include('.partials.form')

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="form-group captcha-error">
                                            <input type="hidden" id="product" name="product" value="{{ $product->id }}">
                                            <input type="hidden" id="district" name="district" value="">
                                            <input type="hidden" id="ip" name="ip" value="{{ request()->ip() }}">

                                            @if(app()->isProduction())
                                                <input type="hidden" class="hiddenRecaptcha form-control"
                                                       name="hiddenRecaptcha" id="hiddenRecaptcha">
                                                <div class="g-recaptcha"
                                                     data-sitekey="6Lc-mScTAAAAAIoo8LHTWRuJhPUXdDFJoGV4ptBg"></div>
                                            @endif

                                            @foreach($errors as $error)
                                                {{$error}}
                                            @endforeach

                                            @if (session('error'))
                                                <div class="alert alert-danger">{{ session('error') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"
                                        class="btn btn-primary btn-lg btn-block">{{ trans('pages.send_form') }}</button>
                            </form>
                        </div>
                    </div>
                @endif


                @if($similar_products && $similar_products->count() > 0)
                    <h4>{{ trans('pages.similar_products') }}</h4>
                    <div class="bxslider-related mx-auto">
                        @foreach($similar_products as $similar_product)
                            <div>
                                <a href="{{ route('product.'.app()->getLocale(), [ 'app_slug' => $application->slug, 'cat_slug' => $category->slug, 'prod_slug' => $similar_product->slug]) }}"
                                   class="bwWrapper">
                                    @if($similar_product->getMainPhotoAttribute())
                                        <img src="{{ $similar_product->getMainPhotoAttribute()->getUrl() }}"
                                             alt="{{ $similar_product->name }}" class="mx-auto img-fluid img-hover">
                                    @endif
                                    <p class="h5 assets-title row-icons--desc mt-4">{{ $similar_product->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($categories && $categories->count() >0)
                <div class="col-12 col-md-4">
                    <h2 class="h3">{{ trans('pages.product_categories') }}</h2>
                    <ul class="list-group">
                        @foreach($categories as $cat)
                            <li class="list-group-item {{ $category->id == $cat->id ? 'active' : '' }}">
                                <a href="{{ route('products.'.app()->getLocale(), ['app_slug' =>$application->slug, 'cat_slug' => $cat->slug]) }}"
                                   class="d-flex justify-content-between align-items-center category_count">
                                    {{ $cat->name }}<span
                                            class="badge badge-primary badge-pill">{{ $cat->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="col-xs-12 spacer"></div>

                    {{--                <h2 class="h3 pt-4">--}}
                    {{--                    <?php echo $aplicatiiptindustrie[0]['nume']; ?><br>--}}
                    {{--                </h2>--}}
                    {{--                <p class="lead text-muted"><?=$nume_ind;?></p>--}}
                    {{--                <ul class="list-unstyled row justify-content-start side-row-ico aplicatii">--}}
                    {{--                    <?php--}}
                    {{--                    foreach ($aplicatii as $apl) {--}}
                    {{--                        if($apl['slug_apl'] != ''){--}}

                    {{--                            if ($apl['slug_apl'] == $slug_apl) {--}}
                    {{--                                $class_apl = "active";--}}
                    {{--                            } else {--}}
                    {{--                                $class_apl = "";--}}
                    {{--                            }--}}
                    {{--                            //echo '<div class="col-xs-3 col-sm-2 col-md-3 aplicatii_padd"><a href="' . base_url() . $categorie . '/' . $apl['slug_apl'] . '/' . $slug_ind . '" data-toggle="tooltip" data-placement="bottom" title="' . $apl['nume_apl'] . '" class="aplicatii_tooltip"><img src="' . HTTP_UPLOADS_PATH . 'images/' . $apl['img'] . '" alt="' . $apl['nume_apl'] . '" class="img-fluid ' . $class_apl . '"></a></div>';--}}
                    {{--                            echo '<li class="col-6 col-sm-4 col-md-3">--}}
                    {{--                            <a href="' . base_url() . $categorie . '/' . $apl['slug_apl'] . '/' . $slug_ind . '/"  data-toggle="tooltip" data-placement="bottom" title="' . $apl['nume_apl'] . '" class="d-flex flex-column text-center">--}}
                    {{--                                <figure class="mx-auto ' . $class_apl . '"><img data-src="' . HTTP_UPLOADS_PATH . 'images/' . $apl['img'] . '" alt="' . $apl['nume_apl'] . '" class="lozad img-fluid lazy-fade "></figure>--}}
                    {{--                            </a>--}}
                    {{--                        </li>';--}}
                    {{--                        }--}}

                    {{--                    }--}}
                    {{--                    ?>--}}
                    {{--                </ul>--}}
                </div>
            @endif
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/js/validate/setprodus.validate.js') }}"></script>
    <script src="{{ asset('/js/countries.js') }}"></script>
    <script src="{{ asset('/js/bxSlider/product_setup.js') }}"></script>
@endsection
