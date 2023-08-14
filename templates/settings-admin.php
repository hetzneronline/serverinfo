<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2016 Bjoern Schiessle <bjoern@schiessle.org>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

script('serverinfo_hetzner', 'script');
script('serverinfo_hetzner', 'smoothie');
script('serverinfo_hetzner', 'Chart.min');

style('serverinfo_hetzner', 'style');

/** @var array $_ */
?>

<div class="server-info-wrapper">

	<!-- SERVER INFOS -->
	<div class="section server-infos">
		<div class="row">
			<div class="col col-6 col-l-12">
				<h2>
					<img class="infoicon" src="<?php p(image_path('core', 'actions/screen.svg')); ?>">
					<?php p($l->t('System')); ?>
				</h2>
			</div>
			<div class="col col-12">
				<p><?php p($l->t('Server time').':'); ?> <strong id="numFilesStorage"><span class="info" id="servertime"></span></strong></p>
			</div>
		</div>
	</div>

	<!-- DISK STATUS -->
	<div class="section disk-status">
		<div class="row">
			<div class="col col-12">
				<h2>
					<img class="infoicon" src="<?php p(image_path('core', 'actions/quota.svg')); ?>">
					<?php p($l->t('Disk')); ?>
				</h2>
			</div>
		</div>

		<p><?php p($l->t('Files:')); ?> <strong id="numFilesStorage"><?php p($_['storage']['num_files']); ?></strong></p>
		<p><?php p($l->t('Storages:')); ?> <strong id="numFilesStorages"><?php p($_['storage']['num_storages']); ?></strong></p>
		<?php if ($_['system']['freespace'] !== null): ?>
			<p><?php p($l->t('Free Space:')); ?> <strong id="systemDiskFreeSpace"><?php p($_['system']['freespace']); ?></strong></p>
		<?php endif; ?>
	</div>

	<!-- ACTIVE USER -->
    <div class="section network-infos">
		<div class="row">
			<div class="col col-12">
				<h2>
					<img class="infoicon" src="<?php p(image_path('core', 'categories/social.svg')); ?>">
					<?php p($l->t('Active users')); ?>
				</h2>
			</div>

			<div class="col col-12">
				<div class="row">
					<div class="col col-4 col-l-6 col-m-12">
						<div class="infobox">
							<div class="interface-wrapper">
								<?php if ($_['storage']['num_users'] > 0) : ?>
									<?php p($l->t('Total users:')); ?>
									<span class="info"><?php p($_['storage']['num_users']); ?></span><br>
								<?php endif; ?>

								<?php if ($_['activeUsers']['last24hours'] > 0) : ?>
									<?php p($l->t('24 hours:')); ?>
									<span class="info"><?php p($_['activeUsers']['last24hours']) ?></span><br>
								<?php endif; ?>

								<?php if ($_['activeUsers']['last1hour'] > 0) : ?>
									<?php p($l->t('1 hour:')); ?>
									<span class="info"><?php p($_['activeUsers']['last1hour']) ?></span><br>
								<?php endif; ?>

								<?php if ($_['activeUsers']['last5minutes'] > 0) : ?>
									<?php p($l->t('5 mins:')); ?>
									<span class="info"><?php p($_['activeUsers']['last5minutes']) ?></span><br>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- SHARES-->
	<?php if ($_['shares']['num_shares'] > 0) : ?>
    <div class="section network-infos">
		<div class="row">
			<div class="col col-12">
				<h2>
					<img class="infoicon" src="<?php p(image_path('core', 'places/files.svg')); ?>">
					<?php p($l->t('Shares')); ?>
				</h2>
			</div>

			<div class="col col-12">
				<div class="row">
					<div class="col col-4 col-l-6 col-m-12">
						<div class="infobox">
							<div class="interface-wrapper">
								<?php if ($_['shares']['num_shares_user'] > 0) : ?>
									<?php p($l->t('Users:')); ?>
									<span class="info"><?php p($_['shares']['num_shares_user']); ?></span><br>
								<?php endif; ?>

								<?php if ($_['shares']['num_shares_groups'] > 0) : ?>
									<?php p($l->t('Groups:')); ?>
									<span class="info"><?php p($_['shares']['num_shares_groups']); ?></span><br>
								<?php endif; ?>

								<?php if ($_['shares']['num_shares_link'] > 0) : ?>
									<?php p($l->t('Links:')); ?>
									<span class="info"><?php p($_['shares']['num_shares_link']); ?></span><br>
								<?php endif; ?>

								<?php if ($_['shares']['num_shares_mail'] > 0) : ?>
									<?php p($l->t('Emails:')); ?>
									<span class="info"><?php p($_['shares']['num_shares_mail']); ?></span><br>
								<?php endif; ?>

								<?php if ($_['shares']['num_fed_shares_sent'] > 0) : ?>
									<?php p($l->t('Federated sent:')); ?>
									<span class="info"><?php p($_['shares']['num_fed_shares_sent']); ?></span><br>
								<?php endif; ?>

								<?php if ($_['shares']['num_fed_shares_received'] > 0) : ?>
									<?php p($l->t('Federated received:')); ?>
									<span class="info"><?php p($_['shares']['num_fed_shares_received']); ?></span><br>
								<?php endif; ?>

								<?php if ($_['shares']['num_shares_room'] > 0) : ?>
									<?php p($l->t('Talk conversations:')); ?>
									<span class="info"><?php p($_['shares']['num_shares_room']); ?></span><br>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<!-- PHP, DATABASE -->
	<div class="section php-database">
		<div class="row">
			<div class="col col-6 col-m-12">
				<!-- PHPINFO -->
				<h2>
					<img class="infoicon" src="<?php p(image_path('core', 'actions/screen.svg')); ?>">
					<?php p($l->t('PHP')); ?>
				</h2>
				<div class="infobox">
					<div class="phpinfo-wrapper">
						<p>
							<?php p($l->t('Version:')); ?>
							<em id="phpVersion"><?php p($_['php']['version']); ?></em>
						</p>
						<p>
							<?php p($l->t('Memory limit:')); ?>
							<em id="phpMemLimit"><?php p($_['php']['memory_limit']); ?></em>
						</p>
						<p>
							<?php p($l->t('Max execution time:')); ?>
							<em id="phpMaxExecTime"><?php p($_['php']['max_execution_time']); ?></em>
						</p>
						<p>
							<?php p($l->t('Upload max size:').' (WebDAV):'); ?>
							<em id="phpUploadMaxSize"><?php p($_['php']['upload_max_filesize']); ?></em>
						</p>
						<p>
							<?php p($l->t('OPcache Revalidate Frequency:')); ?>
							<em id="phpOpcacheRevalidateFreq"><?php p($_['php']['opcache_revalidate_freq']); ?></em>
						</p>
						<p>
							<?php p($l->t('Extensions:')); ?>
							<em id="phpExtensions"><?php p($_['php']['extensions'] !== null ? implode(', ', $_['php']['extensions']) : $l->t('Unable to list extensions')); ?></em>
						</p>
					</div>
				</div>
			</div>

			<div class="col col-6 col-m-12">
				<!-- DATABASE -->
				<h2>
					<img class="infoicon" src="<?php p(image_path('core', 'actions/screen.svg')); ?>">
					<?php p($l->t('Database')); ?>
				</h2>
				<div class="infobox">
					<div class="database-wrapper">
						<p>
							<?php p($l->t('Type:')); ?>
							<em id="databaseType"><?php p($_['database']['type']); ?></em>
						</p>
						<p>
							<?php p($l->t('Version:')); ?>
							<em id="databaseVersion"><?php p($_['database']['version']); ?></em>
						</p>
						<p>
							<?php p($l->t('Size:')); ?>
							<em id="databaseSize"><?php p($_['database']['size']); ?></em>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- EXTERNAL MONITORING-->
	<div class="section monitoring">
		<div class="row">
			<div class="col col-m-12">
				<!-- OCS ENDPOINT -->
				<h2><?php p($l->t('External monitoring tool')); ?></h2>
				<p>
					<?php p($l->t('You can connect an external monitoring tool by using this end point:')); ?>
				</p>
				<div class="monitoring-wrapper">
					<input type="text" readonly="readonly" id="monitoring-endpoint-url" value="<?php echo p($_['ocs']); ?>"/>
					<a class="clipboardButton icon icon-clippy" title="<?php p($l->t('Copy')); ?>" aria-label="<?php p($l->t('Copy')); ?>" data-clipboard-target="#monitoring-endpoint-url"></a>
				</div>
				<p class="settings-hint">
					<?php p($l->t('Appending "?format=json" at the end of the URL gives you the result in JSON.')); ?>
				</p>
				<p>
					<?php p($l->t('To use an access token, please generate one then set it using the following command:')); ?>
					<div><i>occ config:app:set serverinfo_hetzner token --value yourtoken</i></div>
				</p>
				<p>
					<?php p($l->t('Then pass the token with the "NC-Token" header when querying the above URL.')); ?>
				</p>
			</div>
		</div>
	</div>

</div>

