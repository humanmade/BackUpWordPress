<table class="widefat">

		<thead>

			<tr>

				<th scope="col"><?php hmbkp_backups_number( $schedule ); ?></th>
				<th scope="col"><?php _e( 'Size', 'hmbkp' ); ?></th>
				<th scope="col"><?php _e( 'Type', 'hmbkp' ); ?></th>
				<th scope="col"><?php _e( 'Actions', 'hmbkp' ); ?></th>

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

		<td class="hmbkp-no-backups" colspan="4"><?php _e( 'This is where your backups will appear once you have some.', 'hmbkp' ); ?></td>

	</tr>

	<?php endif; ?>

		</tbody>

	</table>