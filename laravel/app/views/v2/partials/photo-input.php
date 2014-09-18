<!--Photo Page-->
<script type="text/x-handlebars-template" id="photo-input-template">
	<div class="photos">
		<div class="photo-system">
			<div class="input-append">
				<input type="text" class="span2 search-query" placeholder="Search Photos*">
				<a class="btn activate-search btn-flat-gray">Search</a>
				<br/>
				<br/>
			<div class="clearfix"></div>
			</div>
			
			<div class="photo-results">
				
			</div>
			
			<div class="chosen">
				<div class="row">
					<div class="col-md-12 col-sm-12 processor-container">
						<div class="photo-processor" style="display:none;">
							<h4>Choose a Filter</h4>
							<img src="{{site_url}}img/photos/nofilter.png" data-process="nofilter" class="active"/>
							<img src="{{site_url}}img/photos/gotham.png" data-process="Gotham"/>
							<img src="{{site_url}}img/photos/toaster.png" data-process="Toaster"/>
							<img src="{{site_url}}img/photos/nashville.png" data-process="Nashville"/>
							<img src="{{site_url}}img/photos/lomo.png" data-process="Lomo"/>
							<img src="{{site_url}}img/photos/kelvin.png" data-process="Kelvin"/>
							<img src="{{site_url}}img/photos/tilt_shift.png" data-process="TiltShift"/>
						<div class="clearfix"></div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 ">
						<div class="chosen-label"></div>
						<div class="processed-label"></div>
						<div class="photo-chosen">
							<div class="loading-container">
								<img src="{{site_url}}images/posts/comment-loading.gif">
							</div>
						</div>
					</div>
					<div class="clearfix"></div>

				</div>
			</div>
		</div>
		
		<input class="processed-image" type="hidden" name="image" value="">
		
		<div class="clearfix"></div>
	</div>
</script>

<!--Photo Item-->
<script type="text/x-handlebars-template" id="photo-item-template">
	<img class="result-image {{id}}" src="{{image_url}}" data-id="{{id}}" data-image="{{image_url_orig}}">
</script>

<!--Pager-->
<script type="text/x-handlebars-template" id="photo-pager-template">
	<a class="pager {{class}}" data-page="{{page}}">{{html}}</a>
</script>