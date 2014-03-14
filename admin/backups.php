<?php $schedules = HMBKP_Schedules::get_instance(); ?>

<div>

	<ul class="subsubsub">

		<?php
		// possible titles
		$titles = array(
			'complete-hourly'      => esc_html__( 'Complete Hourly', 'backupwordpress' ),
			'file-hourly'          => esc_html__( 'File Hourly', 'backupwordpress' ),
			'database-hourly'      => esc_html__( 'Database Hourly', 'backupwordpress' ),
			'complete-twicedaily'  => esc_html__( 'Complete Twicedaily', 'backupwordpress' ),
			'file-twicedaily'      => esc_html__( 'File Twicedaily', 'backupwordpress' ),
			'database-twicedaily'  => esc_html__( 'Database Twicedaily', 'backupwordpress' ),
			'complete-daily'       => esc_html__( 'Complete Daily', 'backupwordpress' ),
			'file-daily'           => esc_html__( 'File Daily', 'backupwordpress' ),
			'database-daily'       => esc_html__( 'Database Daily', 'backupwordpress' ),
			'complete-weekly'      => esc_html__( 'Complete Weekly', 'backupwordpress' ),
			'file-weekly'          => esc_html__( 'File Weekly', 'backupwordpress' ),
			'database-weekly'      => esc_html__( 'Database Weekly', 'backupwordpress' ),
			'complete-fortnightly' => esc_html__( 'Complete Fortnightly', 'backupwordpress' ),
			'file-fortnightly'     => esc_html__( 'File Fortnightly', 'backupwordpress' ),
			'database-fortnightly' => esc_html__( 'Database Fortnightly', 'backupwordpress' ),
			'complete-monthly'     => esc_html__( 'Complete Monthly', 'backupwordpress' ),
			'file-monthly'         => esc_html__( 'File Monthly', 'backupwordpress' ),
			'database-monthly'     => esc_html__( 'Database Monthly', 'backupwordpress' ),
			'complete-manually'    => esc_html__( 'Complete Manually', 'backupwordpress' ),
			'file-manually'        => esc_html__( 'File Manually', 'backupwordpress' ),
			'database-manually'    => esc_html__( 'Database Manually', 'backupwordpress' )
		);


		?>
	<?php foreach ( $schedules->get_schedules() as $schedule ) : ?>
		<li<?php if ( $schedule->get_status() ) { ?> class="hmbkp-running" title="<?php echo esc_attr( strip_tags( $schedule->get_status() ) ); ?>"<?php } ?>><a<?php if ( ! empty ( $_GET['hmbkp_schedule_id'] ) && $schedule->get_id() == $_GET['hmbkp_schedule_id'] ) { ?> class="current"<?php } ?> href="<?php echo esc_url( add_query_arg( 'hmbkp_schedule_id', $schedule->get_id(), HMBKP_ADMIN_URL ) ); ?> "><?php printf( $titles[$schedule->get_slug()] ); ?> <span class="count">(<?php echo esc_html( count( $schedule->get_backups() ) ); ?>)</span></a></li>

	<?php endforeach; ?>

		<li><a class="colorbox" href="<?php esc_attr_e( esc_url( add_query_arg( array( 'action' => 'hmbkp_add_schedule_load' ), is_multisite() ? network_admin_url( 'admin-ajax.php' ) : admin_url( 'admin-ajax.php' ) ) ) ); ?>"> + <?php _e( 'add schedule', 'backupwordpress' ); ?></a></li>

	</ul>

<?php

if ( ! empty( $_GET['hmbkp_schedule_id'] ) )
	$schedule = new HMBKP_Scheduled_Backup( sanitize_text_field( $_GET['hmbkp_schedule_id'] ) );

else {

	$schedules = $schedules->get_schedules();

	$schedule = reset( $schedules );

}

	if ( ! $schedule )
		return; ?>

	<div data-hmbkp-schedule-id="<?php echo esc_attr( $schedule->get_id() ); ?>" class="hmbkp_schedule">

		<?php require( HMBKP_PLUGIN_PATH . '/admin/schedule.php' ); ?>

		<table class="widefat">

		    <thead>

				<tr>

					<th scope="col"><?php hmbkp_backups_number( $schedule ); ?></th>
		    		<th scope="col"><?php _e( 'Size', 'backupwordpress' ); ?></th>
		    		<th scope="col"><?php _e( 'Type', 'backupwordpress' ); ?></th>
		    		<th scope="col"><?php _e( 'Actions', 'backupwordpress' ); ?></th>

				</tr>

		    </thead>

		    <tbody>

    	<?php

			if ( $schedule->get_backups() ) :

				$schedule->delete_old_backups();

					foreach ( $schedule->get_backups() as $file ) :

						if ( ! file_exists( $file ) )
							continue;

							hmbkp_get_backup_row( $file, $schedule );

					endforeach;

			else : ?>

    	<tr>

    		<td class="hmbkp-no-backups" colspan="4"><?php _e( 'This is where your backups will appear once you have some.', 'backupwordpress' ); ?></td>

    	</tr>

    	<?php endif; ?>

		    </tbody>

		</table>

	</div>

</div>

<?php
function hmbkp_backups_number( $schedule, $zero = false, $one = false, $more = false ) {

	$number = count( $schedule->get_backups() );

	if ( $number > 1 )
		$output = str_replace( '%', number_format_i18n( $number ), ( false === $more ) ? __( '% Backups Completed', 'backupwordpress' ) : $more );
	elseif ( $number == 0 )
		$output = ( false === $zero ) ? __( 'No Backups Completed', 'backupwordpress' ) : $zero;
	else // must be one
		$output = ( false === $one ) ? __( '1 Backup Completed', 'backupwordpress' ) : $one;

	echo apply_filters( 'hmbkp_backups_number', $output, $number );
}
