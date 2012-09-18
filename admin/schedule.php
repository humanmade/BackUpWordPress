<?php

// Calculated filesize
$filesize = $schedule->is_filesize_cached() || isset( $recalculate_filesize ) ? '<code title="' . __( 'Backups will be compressed and should be smaller than this.', 'hmbkp' ) . '">' . $schedule->get_filesize() . '</code>' : '<code class="calculating" title="' . __( 'this shouldn\'t take long&hellip;', 'hmbkp' ) . '">calculating the size of your site&hellip;</code>';

// Backup Type
$type = strtolower( hmbkp_human_get_type( $schedule->get_type() ) );

// Backup Time
$day = date_i18n( 'l', $schedule->get_next_occurrence() );

$next_backup = 'title="The next backup will be on ' . date_i18n( get_option( 'date_format' ), $schedule->get_next_occurrence() ) . ' at ' . date_i18n( get_option( 'time_format' ), $schedule->get_next_occurrence() ) . '"';

// Backup Re-occurrence
switch ( $schedule->get_reoccurrence() ) :

	case 'hourly' :

		$reoccurrence = date_i18n( get_option( 'time_format' ), $schedule->get_next_occurrence() ) == '00' ? sprintf( __( 'hourly on the hour', 'hmbkp' ) ) : sprintf( __( 'hourly at %s minutes past the hour', 'hmbkp' ), '<span ' . $next_backup . '>' . str_replace( '0', '', date_i18n( 'i', $schedule->get_next_occurrence() ) ) ) . '</span>';

	break;

	case 'daily' :

		$reoccurrence = sprintf( __( 'daily at %s', 'hmbkp' ), '<span ' . $next_backup . '>' . date_i18n( get_option( 'time_format' ), $schedule->get_next_occurrence() ) . '</span>' );

	break;


	case 'twicedaily' :

		$times[] = date_i18n( get_option( 'time_format' ), $schedule->get_next_occurrence() );
		$times[] = date_i18n( get_option( 'time_format' ), strtotime( '+ 12 hours', $schedule->get_next_occurrence() ) );

		sort( $times );

		$reoccurrence = sprintf( __( 'every 12 hours at %1$s &amp; %2$s', 'hmbkp' ), '<span ' . $next_backup . '>' . reset( $times ) . '</span>', '<span>' . end( $times ) ) . '</span>';

	break;

	case 'weekly' :

		$reoccurrence = sprintf( __( 'weekly on %1$s at %2$s', 'hmbkp' ), '<span ' . $next_backup . '>' . $day . '</span>', '<span>' . date_i18n( get_option( 'time_format' ), $schedule->get_next_occurrence() ) . '</span>' );

	break;

	case 'fortnightly' :

		$reoccurrence = sprintf( __( 'fortnightly on %1$s at %2$s', 'hmbkp' ), '<span ' . $next_backup . '>' . $day . '</span>', '<span>' . date_i18n( get_option( 'time_format' ), $schedule->get_next_occurrence() ) . '</span>' );

	break;


	case 'monthly' :

		$reoccurrence = sprintf( __( 'on the %s of each month at %1$s', 'hmbkp' ), '<span ' . $next_backup . '>' . date_i18n( 'jS', $schedule->get_next_occurrence() ) . '</span>', '<span>' . date_i18n( get_option( 'time_format' ), $schedule->get_next_occurrence() ) . '</span>' );

	break;

	case 'manually' :

		$reoccurrence = __( 'manually', 'hmbkp' );

	break;

endswitch;

$server = '<span title="' . hmbkp_path() . '">' . __( 'this server', 'hmbkp' ) . '</span>';

// Backup to keep
switch ( $schedule->get_max_backups() ) :

	case 1 :

		$backup_to_keep = sprintf( __( 'store the only the last backup on %s', 'hmbkp' ), $server );

	break;

	case 0 :

		$backup_to_keep = sprintf( __( 'don\'t store any backups on %s', 'hmbkp' ), $server );

	break;

	default :

		$backup_to_keep = sprintf( __( 'store only the last %1$s backups on %2$s', 'hmbkp' ), $schedule->get_max_backups(), $server );

endswitch;

foreach ( HMBKP_Services::get_services( $schedule ) as $file => $service )
	$services[] = $service->display(); ?>

<div class="hmbkp-schedule-sentence<?php if ( $schedule->get_status() ) { ?> hmbkp-running<?php } ?>">

	<?php printf( __( 'Backup my %1$s %2$s %3$s, %4$s. %5$s', 'hmbkp' ), $filesize, '<span>' . $type . '</span>', $reoccurrence, $backup_to_keep, implode( '. ', $services ) ); ?>

	<?php hmbkp_schedule_actions( $schedule ); ?>

</div>
