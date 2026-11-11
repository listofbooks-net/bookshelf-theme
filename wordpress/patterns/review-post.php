<?php
/**
 * Title: Book review
 * Slug: bookshelf/review-post
 * Categories: bookshelf-book-blog
 * Keywords: review, book, post, starter
 * Block Types: core/post-content
 * Post Types: post
 * Description: A complete review layout: book details, the review itself with a pull quote, and a verdict box.
 *
 * @package Bookshelf
 */

?>
<!-- wp:pattern {"slug":"bookshelf/book-details"} /-->

<!-- wp:paragraph {"dropCap":true} -->
<p class="has-drop-cap"><?php esc_html_e( 'Start with the moment the book caught you. A line, a scene, or the reason you picked it up. Readers decide in the first paragraph whether your review is worth their time, so make it specific.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Then set the scene without spoilers: who the book follows, where it takes place, and what is at stake. Two or three sentences are enough.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'What it does well', 'bookshelf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Write about the writing: voice, structure, pace, the characters you will remember. Quote a line if it earns its place.', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:pullquote -->
<figure class="wp-block-pullquote"><blockquote><p><?php esc_html_e( '“Grief is a ledger you keep in pencil. You keep thinking you’ll be allowed to rub it out.”', 'bookshelf' ); ?></p><cite><?php esc_html_e( 'The Salt Orchard, chapter nine', 'bookshelf' ); ?></cite></blockquote></figure>
<!-- /wp:pullquote -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( bookshelf_image_uri( 'still-life.svg' ) ); ?>" alt="<?php echo esc_attr__( 'Illustration of a stack of four books and a pear on a windowsill, with a pale arched window behind.', 'bookshelf' ); ?>"/><figcaption class="wp-element-caption"><?php esc_html_e( 'Add a photo of your reading spot, or of the book itself.', 'bookshelf' ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Where it stumbles', 'bookshelf' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Be fair and be concrete. What did not work for you, and who might not mind it?', 'bookshelf' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Who it’s for', 'bookshelf' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li><?php esc_html_e( 'Readers who love slow, place-rooted novels', 'bookshelf' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Probably not for readers who need a twist every chapter', 'bookshelf' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:pattern {"slug":"bookshelf/verdict"} /-->
