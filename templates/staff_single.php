<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

                              <header class="article-header entry-header">

                                <h1 class="entry-title single-title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>

                                <p class="byline entry-meta vcard">

                                  <?php printf( 'Posted %1$s %2$s',
                                     /* the time the post was published */
                                     '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
                                     /* the author of the post */
                                     '<span class="by"> by </span> <span class="entry-author author" itemprop="author" itemscope itemptype="http://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
                                  ); ?>

                                </p>

                              </header> <?php // end article header ?>

                              <section class="entry-content cf" itemprop="articleBody">
                                <?php

                                  the_content();

                                ?>
                              </section> <?php // end article section ?>

                              <footer class="article-footer">

                                <?php printf( 'filed under: %1$s', get_the_category_list(', ') ); ?>

                                <?php the_tags( '<p class="tags"><span class="tags-title"> Tags: </span> ', ', ', '</p>' ); ?>

                              </footer> <?php // end article footer ?>

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
