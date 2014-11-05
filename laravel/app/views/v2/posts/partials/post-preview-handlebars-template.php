<script type="text/javascript">
	Handlebars.registerHelper('isNotLast', function(array, index, options) {
		if( array.length - 1 != index ) {
			return options.fn(this);
		}
		return options.inverse(this);
	});
	Handlebars.registerHelper('increment', function(index, options) {
		return index + 1;
	});
	Handlebars.registerHelper('getBodyPageId', function(index, options) {
		return index == 0 ? 'one' : '';
	});
</script>
<script type="text/x-handlebars-template" id="post-preview-template">
	<section class="post-heading-wrapper">
		<div class="post-heading-container container">
			<div class="row">
				<div class="post-heading col-md-4">
					<h2 itemprop="name" content="{{ title }}">{{ title }}</h2>
					<div class="line"></div>
					<ul class="post-taglines list-inline" itemprop="description">
						<li> {{ tagline_1 }} </li>
						<li> {{ tagline_2 }} </li>
						<li> {{ tagline_3 }} </li>
					</ul>

					<div class="author" itemprop="author" content="{{author}}">
						{{ story_type }} by <a class="author-name" href="#"> {{ author }} </a>
					</div>

					<ul class="post-categories list-inline">
						{{#each categories}}
							<li> 
								<a href="#"> {{ this }} </a>
								{{#isNotLast ../categories @index}}
									/
								{{/isNotLast}}
							</li>
						{{/each}}
					</ul>

				</div>
				<div class="post-image col-md-8" style="background-image: url('{{ image_url }}');">
					<img class="no-show-image" itemprop="image" src="{{ image_url }}">
				</div>
			</div>
		</div>
	</section>

	<section class="post-content-wrapper">
		<div class="post-content-container container">
			{{#each body_array}}
				<div class="row">
					<div class="col-md-10 col-md-offset-1 post-content-page-wrapper">
						<div class="post-content-page" id="{{getBodyPageId @index}}">
							{{{ this }}}
						</div>
					</div>
					<div class="col-md-10 col-md-offset-1 row-divider">
						<span class="page-count">{{ increment @index }}/{{../body_array_count}}</span>
						<div class="clearfix"></div>
					</div>
				</div>
			{{/each}}
		</div>
	</section>
	<div class="modal-header"> <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button> <h4 class="modal-title"> Preview </h4> </div>
</script>