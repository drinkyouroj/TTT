<script type="text/x-handlebars-template" id="notifications-template">
	<div class="notification-container col-md-12">
		{{#ifCond notification.notification_type 'postview'}}
			<div class="post-title">
				<a href="{{site_url}}posts/{{notification.post_alias}}">
					<span class="notification-post-title">{{ notification.post_title }}</span> was viwed {{ notification.view_count}} times!
				</a>
			</div>
		{{/ifCond}}
		{{#ifCond notification.notification_type 'follow'}}
			<div class="follow">
				<a href="{{site_url}}profile/{{notification.users.[0] }}">
					<span class="action-user">{{ notification.users.[0] }}</span>
				</a>
				started following you
			</div>
		{{/ifCond}}
		{{#ifCond notification.notification_type 'post'}}
			<div class="post">
				<a href="{{site_url}}profile/{{notification.users.[0] }}">
					<span class="action-user">{{ notification.users.[0] }}</span>
				</a> 
					submitted a new post
				<a href="{{site_url}}posts/{{notification.post_alias}}">
					<span class="notification-post-title">{{ notification.post_title }}</span>
				</a>
			</div>
		{{/ifCond}}
		{{#ifCond notification.notification_type 'comment'}}
			<div class="comment">
				<a href="{{site_url}}profile/{{notification.users.[0] }}">
					<span class="action-user">{{ notification.users.[0] }}</span>
				</a>
				commented on your post 
				<a href="{{site_url}}posts/{{notification.post_alias}}#comment-{{notification.comment_id}}">
					<span class="notification-post-title">{{ notification.post_title }}</span>
				</a>
			</div>
		{{/ifCond}}
		{{#ifCond notification.notification_type 'reply'}}
			<div class="reply">
				<a href="{{site_url}}profile/{{notification.users.[0] }}">
					<span class="action-user">{{ notification.users.[0] }}</span> 
				</a>
					replied to your commment on 
				<a href="{{site_url}}posts/{{notification.post_alias}}#comment-{{notification.comment_id}}">
					<span class="notification-post-title">{{#substring notification.post_title 25}}{{/substring}}</span>
				</a>
			</div>
		{{/ifCond}}
		
		{{#ifCond notification.notification_type 'repost'}}
			<div class="repost">
				{{#ifGt notification.users.length 1}}
					<a href="{{site_url}}profile/{{#lastArr notification.users }}{{/lastArr}}">
						<span class="action-user">{{#lastArr notification.users }}{{/lastArr}}</span>
					</a> 
					reposted your post 
					<a href="{{site_url}}posts/{{notification.post_alias}}">
						<span class="notification-post-title">{{ notification.post_title }}</span> 
					</a>
					along with 
					<span class="others">{{#folks notification.users }}{{/folks}} 
						<ul>
							{{#each notification.users}}
								{{#ifGt @index 0}}
								<li>
									<a href="{{../site_url}}profile/{{this}}">
										{{this}}
									</a>
								</li>
								{{/ifGt}}
							{{/each}}
						</ul>
					</span>
				{{else}}
				<a href="{{site_url}}profile/{{notification.users.[0]}}">
					<span class="action-user">{{ notification.users.[0] }}</span>
				</a> 
					reposted your post 
				<a href="{{site_url}}posts/{{notification.post_alias}}">
					<span class="notification-post-title">{{ notification.post_title }}</span>
				</a>
				{{/ifGt}}
			</div>
		{{/ifCond}}

		{{#ifCond notification.notification_type 'like'}}
			<div class="like">
				{{#ifGt notification.users.length 1}}
					<a href="{{site_url}}profile/{{#lastArr notification.users }}{{/lastArr}}">
						<span class="action-user">{{#lastArr notification.users }}{{/lastArr}}</span> 
					</a>
					liked your post 
					<a href="{{site_url}}posts/{{notification.post_alias}}">
						<span class="notification-post-title">{{#substring notification.post_title 25}}{{/substring}}</span> 
					</a>
					along with 
					<span class="others">{{#folks notification.users }}{{/folks}} 
						<ul>
							{{#each notification.users}}
								{{#ifGt @index 0}}
									<li>
										<a href="{{../site_url}}profile/{{this}}">
											{{this}}
										</a>
									</li>
								{{/ifGt}}
							{{/each}}
						</ul>
					</span>
					
				{{else}}
					<a href="{{site_url}}profile/{{notification.users.[0]}}">
						<span class="action-user">{{ notification.users.[0] }}</span> 
					</a>
						liked your post
					<a href="{{site_url}}posts/{{notification.post_alias}}">
						<span class="notification-post-title">{{#substring notification.post_title 25}}{{/substring}}</span>
					</a>
				{{/ifGt}}
			</div>
		{{/ifCond}}

	</div>
</script>