<?php $pirouette_tm_footer_top = esc_attr( get_theme_mod( 'footer_top', esc_html__( 'Back to top of the page', 'pirouette' ) ) ); if( ! empty( $pirouette_tm_footer_top ) ) : ?>
    <a href="#top" class="ct-smooth-scroll" id="ct-scroll-top"><em class="ti-angle-up"></em> <?php echo esc_attr( $pirouette_tm_footer_top ); ?></a>
<?php endif; ?>
