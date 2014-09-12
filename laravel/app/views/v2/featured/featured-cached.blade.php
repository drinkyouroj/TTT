
	<div class="main-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					@if(isset($featured{0}))
						<?php $main = $featured{0}->post; ?>
						@if(is_object($main))
							<div class="featured-item">
								<div class="text">
									<h2>{{$main->title}}</h2>
									<p>
										{{$main->body}}
										<br/>
										<br/>
										<a href="">Read More</a>
									</p>
								</div>
								<div class="image" style="background-image: url({{Config::get('app.imageurl')}}/{{$main->image}} )">
								</div>
							</div>
							<?php unset($featured{0});?>
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="middle-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					@foreach($featured as $k=>$f)
						
						@if($k == 3)
							{{--if this is the third item--}}
							@if(Auth::check() && is_object($from_feed))
								{{--Somethign from the user's feed--}}
								@include('v2.partials.featured-listing', array('post'=> $from_feed->post))
							@else
								{{--Signup box thing--}}
								<div class="post-container weird max thing">
									SIGNUP BOX THING
								</div>
							@endif
							<!--Feed Listing or Signup-->
							<div class="bar"></div>
						@endif
						
						{{--Under normal circumstances...--}}
						@include('v2.partials.featured-listing', array('post'=> $f->post))
						<!--{{$f->position}}-->

						@if($k == 5)
							<?php break;?>
						@endif
					@endforeach
				</div>
			</div>
		</div>
	</div>