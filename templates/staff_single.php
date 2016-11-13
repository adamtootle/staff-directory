<?php get_header(); ?>

            <?php
                $published = get_post_status( $post->$ID ) == 'published' ? '.published' : '';
            ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">
					<main id="main" class="faculty-main <?php echo $published; ?>" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

                              <header class="article-header entry-header">

                                <h1 class="entry-title single-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>

                                <p class="entry-meta vcard">

                                  <?php printf( 'Updated %1$s',
                                     /* the time the post was published */
                                     '<time class="entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>'
                                  ); ?>

                                </p>

                              </header> <?php // end article header ?>

                              <div class="faculty-profile-info entry-content <?php echo $published; ?>">
                                  <?php if (has_post_thumbnail( $post->ID ) ){
                                          $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
                                          ?>
                                              <img src="<?php echo $image[0]; ?>" alt="profile image">
                                          <?php
                                      } else {
                                          //Do nothing
                                      }
                                  ?>
                                  <?php //All shortcodes simply return the appropriate strings, so to print them all without the staff loop we have to echo do_shortcode() ?>
                                  <div class="staff-directory-profile-info <?php echo $published; ?>">
                                          <div class="single-staff">
                                              <?php if(do_shortcode("[name]")): ?>
                                                  <div class="name" title="Name">
                                                      <i class="fa fa-user" aria-hidden="true"></i>
                                                      <?php echo do_shortcode("[name]"); ?>
                                                  </div>
                                              <?php endif; ?>
                                              <?php if(do_shortcode("[position]")): ?>
                                                  <div class="position" title="Position">
                                                      <i class="fa fa-briefcase" aria-hidden="true"></i>
                                                      <?php echo do_shortcode("[position]"); ?>
                                                  </div>
                                              <?php endif; ?>
                                              <?php if(do_shortcode("[email]")): ?>
                                                  <div class="email" title="E-mail address">
                                                      <i class="fa fa-envelope" aria-hidden="true"></i>
                                                      <?php echo do_shortcode("[email]"); ?>
                                                  </div>
                                              <?php endif; ?>
                                              <?php if(do_shortcode("[phone_number]")): ?>
                                                  <div class="phone" title="Phone number">
                                                      <i class="fa fa-phone" aria-hidden="true"></i>
                                                      <?php echo do_shortcode("[phone_number]"); ?>
                                                  </div>
                                              <?php endif; ?>
                                          </div>
                                  </div>

                              </div>

                              <section class="faculty-profile-content entry-content <?php echo $published; ?>" itemprop="articleBody">
                                <?php
                                    $content = get_the_content();

                                    if($content){
                                        the_content();
                                    } else {
                                        echo "<p> No biography found. </p>";
                                    }

                                ?>
                              </section> <?php // end article section ?>

                              <?php //comments_template(); ?>

                            </article> <?php // end article ?>

						<?php endwhile; ?>

						<?php else : ?>

							<article id="post-not-found" class="hentry cf">
									<header class="article-header">
										<h1>Post not found</h1>
									</header>
									<section class="entry-content">
										<p>The content you are trying to access does not seem to exist.</p>
									</section>
							</article>

						<?php endif; ?>

					</main>

					<?php //get_sidebar(); ?>

				</div>

			</div>

<?php get_footer(); ?>
