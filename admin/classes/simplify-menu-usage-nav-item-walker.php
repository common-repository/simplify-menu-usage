<?php
namespace SimplifyMenuUsage\Core;
use SimplifyMenuUsage\Core\Screen_Helper;
use \Walker_Nav_Menu_Edit;

class Simplify_Menu_Usage_Nav_Item_Walker extends Walker_Nav_Menu_Edit {
	
        private $current_index = 0;
        /**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker_Nav_Menu::start_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   Not used.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker_Nav_Menu::end_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   Not used.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu::start_el()
	 * @since 3.0.0
	 *
	 * @global int $_wp_nav_menu_max_depth
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   Not used.
	 * @param int      $id     Not used.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
                global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
          	ob_start();
                
                if($this->current_index === 0){
                    $screen_helper = new Screen_Helper();
                    if($screen_helper->is_screen_like('nav-menus')){
                        $inserting_downwards_behavior = get_option('smu_down_insert', 'default');
                        $display_menu_description = get_option('smu_display_menu_description', 'yes');
                        ${'inserting_downwards_behavior'} = $inserting_downwards_behavior;
                        ${'display_menu_description'} = $display_menu_description;
                        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/explaination-inserting-behavior-menu.php';
                    }
                }
                
		$item_id      = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = false;

		if ( 'taxonomy' == $item->type ) {
			$original_object = get_term( (int) $item->object_id, $item->object );
			if ( $original_object && ! is_wp_error( $original_title ) ) {
				$original_title = $original_object->name;
			}
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			if ( $original_object ) {
				$original_title = get_the_title( $original_object->ID );
			}
		} elseif ( 'post_type_archive' == $item->type ) {
			$original_object = get_post_type_object( $item->object );
			if ( $original_object ) {
				$original_title = $original_object->labels->archives;
			}
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive' ),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: Title of an invalid menu item. */
			$title = sprintf( __( '%s (Invalid)' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: Title of a menu item in draft status. */
			$title = sprintf( __( '%s (Pending)' ), $item->title );
		}

		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

		$submenu_text = '';
		if ( 0 == $depth ) {
			$submenu_text = 'style="display: none;"';
		}

		?>
		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode( ' ', $classes ); ?>">
			<div class="menu-item-bar">
				<div class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title">
                                            <?php if ($title !== $original_title) : ?>
                                                <?php
                                                    printf(esc_html( $title ) . ' (%s)', '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' );
                                                ?>
                                            <?php else: ?>
                                                <?php echo esc_html( $title ); ?>
                                            <?php endif; ?>
                                            </span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item' ); ?></span></span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<span class="item-order hide-if-js">
							<?php
							printf(
								'<a href="%s" class="item-move-up" aria-label="%s">&#8593;</a>',
								wp_nonce_url(
									add_query_arg(
										array(
											'action'    => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								),
								esc_attr__( 'Move up' )
							);
							?>
							|
							<?php
							printf(
								'<a href="%s" class="item-move-down" aria-label="%s">&#8595;</a>',
								wp_nonce_url(
									add_query_arg(
										array(
											'action'    => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								),
								esc_attr__( 'Move down' )
							);
							?>
						</span>
						<?php
						if ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) {
							$edit_url = admin_url( 'nav-menus.php' );
						} else {
							$edit_url = add_query_arg(
								array(
									'edit-menu-item' => $item_id,
								),
								remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) )
							);
						}

						printf(
							'<a class="item-edit" id="edit-%s" href="%s" aria-label="%s"><span class="screen-reader-text">%s</span></a>',
							$item_id,
							$edit_url,
							esc_attr__( 'Edit menu item' ),
							__( 'Edit' )
						);
                                                
                                                printf(
                                                        '<a class="item-delete custom-nav-item-delete submitdelete deletion" id="delete-%s" href="%s">%s</a>',
                                                        $item_id,
                                                        wp_nonce_url(
                                                                add_query_arg(
                                                                        array(
                                                                                'action'    => 'delete-menu-item',
                                                                                'menu-item' => $item_id,
                                                                        ),
                                                                        admin_url( 'nav-menus.php' )
                                                                ),
                                                                'delete-menu_item_' . $item_id
                                                        ),
                                                        '<span class="dashicons dashicons-trash"></span>'
                                                );
					
                                                ?>

                                                <fieldset class="not-active custom-field-move">
                                                    <button type="button" class="button-link menus-move custom-menus-move-up" data-dir="up"><span class="dashicons dashicons-arrow-up-alt2"></span></button>
                                                    <button type="button" class="button-link menus-move custom-menus-move-down" data-dir="down"><span class="dashicons dashicons-arrow-down-alt2"></span></button>
                                                    <button type="button" class="button-link menus-move custom-menus-move-left" data-dir="left"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
                                                    <button type="button" class="button-link menus-move custom-menus-move-right" data-dir="right"><span class="dashicons dashicons-arrow-right-alt2"></span></button>
                                                </fieldset>
                                                						
                                                <?php ?>
					</span>
				</div>
                            <div data-element="<?php esc_attr_e($item_id); ?>" class="menu-simplify-usage quick-positions">
                                <a href="#" data-dir="left" class="move-out-element"><span class="dashicons dashicons-arrow-left-alt"></span></a>
                                <a href="#" data-dir="up" class="move-before-element"><span class="dashicons dashicons-arrow-up-alt"></span></a>
                                <a href="#" data-dir="down" class="move-after-element"><span class="dashicons dashicons-arrow-down-alt"></span></a>
                                <a href="#" data-dir="right" class="move-below-element"><span class="dashicons dashicons-arrow-right-alt"></span></a>
                            </div>
			</div>

			<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $item_id; ?>">
				<?php if ( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo $item_id; ?>">
							<?php _e( 'URL' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-wide">
					<label for="edit-menu-item-title-<?php echo $item_id; ?>">
						<?php _e( 'Navigation Label' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="field-title-attribute field-attr-title description description-wide">
					<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
						<?php _e( 'Title Attribute' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
						<?php _e( 'Open link in a new tab' ); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
						<?php _e( 'CSS Classes (optional)' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode( ' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
						<?php _e( 'Link Relationship (XFN)' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>
				<p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo $item_id; ?>">
						<?php _e( 'Description' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
						<span class="description"><?php _e( 'The description will be displayed in the menu if the current theme supports it.' ); ?></span>
					</label>
				</p>

				<?php
				/**
				 * Fires just before the move buttons of a nav menu item in the menu editor.
				 *
				 * @since 5.4.0
				 *
				 * @param int      $item_id Menu item ID.
				 * @param WP_Post  $item    Menu item data object.
				 * @param int      $depth   Depth of menu item. Used for padding.
				 * @param stdClass $args    An object of menu item arguments.
				 * @param int      $id      Nav menu ID.
				 */
				do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args, $id );
				?>

				<fieldset class="field-move hide-if-no-js description description-wide">
					<span class="field-move-visual-label" aria-hidden="true"><?php _e( 'Move' ); ?></span>
					<button type="button" class="button-link menus-move menus-move-up" data-dir="up"><?php _e( 'Up one' ); ?></button>
					<button type="button" class="button-link menus-move menus-move-down" data-dir="down"><?php _e( 'Down one' ); ?></button>
					<button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
					<button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
					<button type="button" class="button-link menus-move menus-move-top" data-dir="top"><?php _e( 'To the top' ); ?></button>
				</fieldset>

				<div class="menu-item-actions description-wide submitbox">
					<?php if ( 'custom' !== $item->type && false !== $original_title ) : ?>
						<p class="link-to-original">
							<?php
							/* translators: %s: Link to menu item's original object. */
							printf( __( 'Original: %s' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' );
							?>
						</p>
					<?php endif; ?>

					<?php
					printf(
						'<a class="item-delete submitdelete deletion" id="delete-%s" href="%s">%s</a>',
						$item_id,
						wp_nonce_url(
							add_query_arg(
								array(
									'action'    => 'delete-menu-item',
									'menu-item' => $item_id,
								),
								admin_url( 'nav-menus.php' )
							),
							'delete-menu_item_' . $item_id
						),
						__( 'Remove' )
					);
					?>
					<span class="meta-sep hide-if-no-js"> | </span>
					<?php
					printf(
						'<a class="item-cancel submitcancel hide-if-no-js" id="cancel-%s" href="%s#menu-item-settings-%s">%s</a>',
						$item_id,
						esc_url(
							add_query_arg(
								array(
									'edit-menu-item' => $item_id,
									'cancel'         => time(),
								),
								admin_url( 'nav-menus.php' )
							)
						),
						$item_id,
						__( 'Cancel' )
					);
					?>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
                $this->current_index += 1;
	}

}