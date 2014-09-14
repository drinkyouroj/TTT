
	<div class="main-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					@if(isset($featured{0}))
						<?php $main = $featured{0}->post; ?>
						@if(is_object($main))
							<div class="featured-item">
								<div class="text col-md-5">
									<h2>{{$main->title}}</h2>
									<div class="line"></div>
									<ul class="post-taglines list-inline">
										<li> {{ $main->tagline_1 }} </li>
										<li> {{ $main->tagline_2 }} </li>
										<li> {{ $main->tagline_3 }} </li>
									</ul>
									<p class="excerpt">
										{{substr($main->body, 0,200)}}
										<br/>
										<br/>
										<a class="read-more" href="">Read More</a>
									</p>
								</div>
								<div class="image col-md-7" style="background-image: url({{Config::get('app.imageurl')}}/{{$main->image}} )">
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
				@foreach($featured as $k=>$f)
					
					@if($k == 3)
						{{--if this is the third item--}}
						@if(Auth::check() && is_object($from_feed))
							{{--Somethign from the user's feed--}}
							@include('v2.partials.featured-listing', array('post'=> $from_feed->post))
						@else
							{{--Signup box thing--}}
							<div class="col-md-4 col-sm-6">
								<div class="post-container">
									<div class="signup-box">
										<div class="signup-top">
										</div>
										<div class="signup-content">
											<img class="join-community" src="{{ URL::to('images/featured/join-community.png') }}" alt="Join the Community">
											<div class="line"></div>
											<a class="btn-flat-blue" href="">Create An Account</a>
											<ul class="account-bullets">
												<li>post your own stories</li>
												<li>follow users</li>
												<li>save stories</li>
												<li>comment and join the discussion</li>
											</ul>
										</div>
										<div class="signup-bottom">
										</div>
									</div>
								</div>
							</div>
						@endif
						<!--Feed Listing or Signup-->
						<div class="bar"></div>
					@endif
					
					{{--Under normal circumstances...--}}
					<div class="col-md-4 col-sm-6">
						@include('v2.partials.featured-listing', array('post'=> $f->post))
						<!--{{$f->position}}-->
					</div>
						@if($k == 5)
							<?php break;?>
						@endif
				@endforeach
			</div>
		</div>
		<div class="clearfix"></div>
	</div>