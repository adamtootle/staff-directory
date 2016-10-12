<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

                              <header class="article-header entry-header">

                                <h1 class="entry-title single-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>

                                <p class="byline entry-meta vcard">

                                  <?php printf( 'Updated %1$s',
                                     /* the time the post was published */
                                     '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>'
                                  ); ?>

                                </p>

                              </header> <?php // end article header ?>

                              <section class="entry-content cf" itemprop="articleBody">
                                <?php

                                  the_content();

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
