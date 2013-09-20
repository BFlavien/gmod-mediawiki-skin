<?php
	/**
	* Skin file for skin GMODSkinFoundation.
	*
	* @file
	* @ingroup Skins
	*/

	/**
	 * SkinTemplate class for GMODSkinFoundation skin
	 * @ingroup Skins
	 */
	class SkinGMODSkinFoundation extends SkinTemplate {

		var $skinname = 'gmodskin-foundation', $stylename = 'gmodskin-foundation',
			$template = 'GMODSkinFoundationTemplate', $useHeadElement = true;

		public function initPage( OutputPage $out ) {
			global $wgLocalStylePath;

			parent::initPage( $out );

			// Append CSS which includes IE only behavior fixes for hover support -
			// this is better than including this in a CSS fille since it doesn't
			// wait for the CSS file to load before fetching the HTC file.
			$min = $this->getRequest()->getFuzzyBool( 'debug' ) ? '' : '.min';
			$out->addHeadItem( 'metatag-viewport', '<meta name="viewport" content="width=device-width,initial-scale=1">');
		}

		/**
		 * @param $out OutputPage object
		 */
		function setupSkinUserCss( OutputPage $out ){
			parent::setupSkinUserCss( $out );
			$out->addModuleStyles( 'skins.gmodskin-foundation' );
			$out->addStyle('http://fonts.googleapis.com/css?family=Roboto:400,900,700,500,300,100', 'screen');
		}

	}

	/**
	 * BaseTemplate class for My Skin skin
	 * @ingroup Skins
	 */
	class GMODSkinFoundationTemplate extends BaseTemplate {
		/**
		 * Like msgWiki() but it ensures edit section links are never shown.
		 *
		 * Needed for Mediawiki 1.19 & 1.20 due to bug 36975:
		 * https://bugzilla.wikimedia.org/show_bug.cgi?id=36975
		 *
		 * @param $message Name of wikitext message to return
		 */
		function msgWikiNoEdit( $message ) {
			global $wgOut;
			global $wgParser;

			$popts = new ParserOptions();
			$popts->setEditSection( false );
			$text = wfMessage( $message )->text();
			return $wgParser->parse( $text, $wgOut->getTitle(), $popts )->getText();
		}

		/**
		 * Outputs the entire contents of the page
		 */
		public function execute() {
			// Suppress warnings to prevent notices about missing indexes in $this->data
			wfSuppressWarnings();

			$this->html( 'headelement' ); ?>

				<!-- Author content here -->

				<div class="row full-width">
					<!-- SEARCHBAR -->

					<div class="large-4 small-12 push-8 columns">
						<!-- <div id="mw-bar-top" class="hide-for-medium-up"> -->
						<div id="mw-bar-top">
							<button id="mw-btn-menu" class="hide-for-medium-up">
								<?php echo '<img alt="menu button" src="'.$this->getSkin()->getSkinStylePath( '/icon/icon-menu.png').'"/>' ?>
							</button>
							<form id="mw-search-bar" action="<?php $this->text( 'wgScript' ); ?>">
								<div id="mw-search_bar_div1">
									<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
									<?php echo $this->makeSearchButton( 'image', array( 'src' => $this->getSkin()->getSkinStylePath( '/icon/icon-search.png'), 'id' => 'mw-searchButton') ); ?>
									<div id="mw-wrap-input"> <?php echo $this->makeSearchInput( array( 'id' => 'mw-searchInput' ) ); ?></div>
								</div>
							</form>
						</div>
					</div>

					<!-- CONTENT NAVIGATION TAB-->

					<div class="large-3 small-12 pull-2 columns">
						<button id="mw-content-navigation-btn">Content actions</button>
					</div>

					<!-- PERSONAL LINKS TAB -->

					<div class="large-3 small-12 pull-4 columns">
						<button id="mw-personal-links-btn">Personal links</button>
					</div>
				</div>

				<!-- CONTENT NAVIGATION -->

				<div class="row full-width" id="mw-content-navigation">
					<div class="large-10 large-offset-2">
						<!--
						<?php foreach ( $this->data['content_navigation'] as $category => $tabs ) {
							// are there any subcategories?
							if( count($tabs) != 0 ) {
						?>
							--><ul>
								<li class="mw-content-nav-title"><?php echo $category; ?></li>
								<?php foreach ( $tabs as $key => $tab ) {
									if (array_key_exists('redundant', $tab) && $tab['redundant'] == 1)
									{ // omit the redundant tab
									}
									else {
										echo $this->makeListItem( $key, $tab );
									}
								} ?>
							</ul><!--
						<?php }
						} ?>
					--></div>
				</div>

				<!-- PERSONAL LINKS -->

				<div class="row full-width" id="mw-personal-links">
					<div class="large-10 large-offset-2">
						<ul>
							<?php foreach ( $this->getPersonalTools() as $key => $item ) {
								echo $this->makeListItem($key, $item);
							} ?>
						</ul>
					</div>
				</div>

				<div class="row full-width">
					<!-- SIDEBAR -->

					<div class="large-2 small-12 columns" id="mw-sidebar">
						<!-- <p>from $this->getSidebar, with debug code</p> -->
						<?php foreach ( $this->getSidebar() as $boxName => $box ) { ?>
							<!-- <p>boxName = <?php echo htmlspecialchars( $boxName ); ?></p> -->
							<div id='<?php echo Sanitizer::escapeId( $box['id'] ) ?>'<?php echo Linker::tooltip( $box['id'] ) ?>>
								<?php if( $boxName != "TOOLBOXEND"){ ?>
									<h5><?php echo htmlspecialchars( $box['header'] ); ?></h5>
								<?php } ?>
								<?php if ( is_array( $box['content'] ) ) { ?>
									<ul>
										<?php foreach ( $box['content'] as $key => $item ) {
											echo $this->makeListItem( $key, $item );
										} ?>
									</ul>
								<?php } else {
									echo $box['content'];
								} ?>
							</div>
						<?php } ?>
					</div>

					<div class="large-10 small-12 columns" id="mw-content">
						<div class="row">
							<div id="mw-js-message"></div>
							<?php
								foreach( array( 'newtalk', 'sitenotice' ) as $msg ) {
									if( $this->data[$msg] ) {
										echo "<div id='$msg' class='message'>";
										$this->html( $msg );
										echo '</div>';
									}
								}
							?>
						</div>
						<h1 id="mw-firstHeading" class="firstHeading text-right"><?php $this->html('title') ?></h1>

						<!-- SUBTITLE AND UNDELETE -->
						<?php
							foreach( array('subtitle', 'undelete' ) as $msg ) {
								if( $this->data[$msg] ) {
									echo "<div id='$msg' class='message'>";
									$this->html( $msg );
									echo '</div>';
								}
							}
						?>
						<div id="mw-bodyContent">
							<?php $this->html( 'bodytext' ) ?>
						</div>
						<?php $this->html( 'catlinks' ); ?>
						<?php $this->html( 'dataAfterContent' ); ?>

						<div id="footer">
							<ul id="footer-icons">
								<?php foreach ( $this->getFooterIcons( "icononly" ) as $blockName => $footerIcons ) { ?>
									<li>
										<?php foreach ( $footerIcons as $icon ) { ?>
											<?php echo $this->getSkin()->makeFooterIcon( $icon ); ?>
										<?php } ?>
									</li>
								<?php } ?>
							</ul>
							<?php
								foreach ( $this->getFooterLinks() as $category => $links ) {
									if ( $category === 'info' ) {
										foreach ( $links as $key ) {
											echo '<p>';
											$this->html( $key );
											echo '</p>';
										}
									} else {
										echo '<ul id="footer-ul">';
										foreach ( $links as $key ) {
											echo '<li>';
											$this->html( $key );
											echo '</li>';
										}
										echo '</ul>';
									}
								}
							?>
						</div>
					</div>


				</div>

				<script>
				//	document.write('<script src=' + ('__proto__' in {} ? 'skins/gmodskin-foundation/js/vendor/zepto' : 'skins/gmodskin-foundation/js/vendor/jquery') + '.js><\/script>');
				</script>

				<script src="skins/gmodskin-foundation/js/foundation/foundation.js"></script>
				<!--

				<script src="js/foundation/foundation.js"></script>

				<script src="js/foundation/foundation.alerts.js"></script>

				<script src="js/foundation/foundation.clearing.js"></script>

				<script src="js/foundation/foundation.cookie.js"></script>

				<script src="js/foundation/foundation.dropdown.js"></script>

				<script src="js/foundation/foundation.forms.js"></script>

				<script src="js/foundation/foundation.joyride.js"></script>

				<script src="js/foundation/foundation.magellan.js"></script>

				<script src="js/foundation/foundation.orbit.js"></script>

				<script src="js/foundation/foundation.reveal.js"></script>

				<script src="js/foundation/foundation.section.js"></script>

				<script src="js/foundation/foundation.tooltips.js"></script>

				<script src="js/foundation/foundation.topbar.js"></script>

				<script src="js/foundation/foundation.interchange.js"></script>

				<script src="js/foundation/foundation.placeholder.js"></script>

				<script src="js/foundation/foundation.abide.js"></script>

				-->

				<script>
					$(document).foundation();
				</script>

				<script>
					(function(){
						$('#mw-content-navigation-btn').bind('click', function(){
							if( $('#mw-content-navigation').css('display') == 'none'){
								$('#mw-content-navigation').css('display', 'block');
								$('#mw-content-navigation-btn').css('background-color', '#0c65cc');
							}
							else{
								$('#mw-content-navigation').css('display', 'none');
								$('#mw-content-navigation-btn').css('background-color', '#0d72e5');
							}

							$('#mw-personal-links').css('display', 'none');
							$('#mw-personal-links-btn').css('background-color', '#0d72e5');
						});

						$('#mw-content-navigation').bind('mouseleave', function(){
							$('#mw-content-navigation').css('display', 'none');
							$('#mw-content-navigation-btn').css('background-color', '#0d72e5');
						});



						$('#mw-personal-links-btn').bind('click', function(){
							if( $('#mw-personal-links').css('display') == 'none'){
								$('#mw-personal-links').css('display', 'block');
								$('#mw-personal-links-btn').css('background-color', '#0c65cc');
							}
							else{
								$('#mw-personal-links').css('display', 'none');
								$('#mw-personal-links-btn').css('background-color', '#0d72e5');
							}

							$('#mw-content-navigation').css('display', 'none');
							$('#mw-content-navigation-btn').css('background-color', '#0d72e5');
						});

						$('#mw-personal-links').bind('mouseleave', function(){
							$('#mw-personal-links').css('display', 'none');
							$('#mw-personal-links-btn').css('background-color', '#0d72e5');
						});
					})();
				</script>

				<!-- End of Author content -->
				<?php $this->printTrail(); ?>
			</body>
		</html>

		<?php wfRestoreWarnings();
		}

		protected function renderPortals( $sidebar ) {

		}
	}
?>
