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

(function ($, OC) {

	var activeUsersChart,
		sharesChart;

	$(document).ready(function () {
		initServerTime();

		setHumanReadableSizeToElement("databaseSize");
		setHumanReadableSizeToElement("phpMemLimit");
		setHumanReadableSizeToElement("phpUploadMaxSize");
		setHumanReadableSizeToElement("systemDiskFreeSpace");

		initMonitoringLinkToClipboard();
		$("#monitoring-endpoint-url").on('click', function () {
			$(this).select();
		});
	});

	$(window).load(function(){
		resizeSystemCharts();
	});

	$(window).resize(function () {
		resizeSystemCharts();
	});

	function getThemedPrimaryColor() {
		return OCA.Theming ? OCA.Theming.color : 'rgb(54, 129, 195)';
	}

	function getThemedPassiveColor() {
		return OCA.Theming && OCA.Theming.inverted ? 'rgb(55, 55, 55)' : 'rgb(200, 200, 200)';
	}

	/**
	 * Reset all canvas widths on window resize so canvas is responsive
	 */
	function resizeSystemCharts() {
		var activeUsersCanvas = $("#activeuserscanvas"),
			activeUsersCanvasWidth = activeUsersCanvas.parents('.infobox').width() - 30,
			shareCanvas = $("#sharecanvas"),
			shareCanvasWidth = shareCanvas.parents('.infobox').width() - 30;

		// We have to set css width AND attribute width
		activeUsersCanvas.width(activeUsersCanvasWidth);
		activeUsersCanvas.attr('width', activeUsersCanvasWidth);
		shareCanvas.width(shareCanvasWidth);
		shareCanvas.attr('width', shareCanvasWidth);

		updateShareStatistics();
		updateActiveUsersStatistics();
	}

	function updateShareStatistics() {

		var shares = $('#sharecanvas').data('shares'),
			sharesData = [shares.num_shares_user,
				shares.num_shares_groups,
				shares.num_shares_link,
				shares.num_shares_mail,
				shares.num_fed_shares_sent,
				shares.num_fed_shares_received,
				shares.num_shares_room
			],
			stepSize = 0;

		if (Math.max.apply(null, sharesData) < 10) {
			stepSize = 1;
		}

		if (typeof sharesChart === 'undefined') {
			var ctx = document.getElementById("sharecanvas");

			sharesChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: [
						t('serverinfo_hetzner', 'Users'),
						t('serverinfo_hetzner', 'Groups'),
						t('serverinfo_hetzner', 'Links'),
						t('serverinfo_hetzner', 'Emails'),
						t('serverinfo_hetzner', 'Federated sent'),
						t('serverinfo_hetzner', 'Federated received'),
						t('serverinfo_hetzner', 'Talk conversations'),
					],
					datasets: [{
						label: " ",
						data: sharesData,
						backgroundColor: [
							'rgba(0, 76, 153, 0.2)',
							'rgba(51, 153, 255, 0.2)',
							'rgba(207, 102, 0, 0.2)',
							'rgba(255, 178, 102, 0.2)',
							'rgba(0, 153, 0, 0.2)',
							'rgba(153, 255, 51, 0.2)',
							'rgba(178, 102, 255, 0.2)'
						],
						borderColor: [
							'rgba(0, 76, 153, 1)',
							'rgba(51, 153, 255, 1)',
							'rgba(207, 102, 0, 1)',
							'rgba(255, 178, 102, 1)',
							'rgba(0, 153, 0, 1)',
							'rgba(153, 255, 51, 1)',
							'rgba(178, 102, 255, 1)'
						],
						borderWidth: 1
					}]
				},
				options: {
					plugins: { legend: { display: false } },
					scales: {
						yAxes: {
							ticks: {
								min: 0,
								stepSize: stepSize
							}
						}
					}
				}
			});
		}

		sharesChart.update();
	}

	function updateActiveUsersStatistics() {

		var activeUsers = $('#activeuserscanvas').data('users'),
			activeUsersData = [activeUsers.last24hours, activeUsers.last1hour, activeUsers.last5minutes],
			stepSize = 0;

		if (Math.max.apply(null, activeUsersData) < 10) {
			stepSize = 1;
		}

		if (typeof activeUsersChart === 'undefined') {
			var ctx = document.getElementById("activeuserscanvas");

			activeUsersChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: [
						t('serverinfo_hetzner', '24 hours'),
						t('serverinfo_hetzner', '1 hour'),
						t('serverinfo_hetzner', '5 mins')
					],
					datasets: [{
						label: " ",
						data: activeUsersData,
						fill: false,
						borderColor: [getThemedPrimaryColor()],
						borderWidth: 1,
						borderDashOffset: 0.0,
						borderJoinStyle: 'miter',
						pointBorderColor: getThemedPrimaryColor(),
						pointBackgroundColor: getThemedPassiveColor(),
						pointBorderWidth: 1,
						pointHoverRadius: 5,
						pointHoverBackgroundColor: getThemedPrimaryColor(),
						pointHoverBorderColor: getThemedPrimaryColor(),
						pointHoverBorderWidth: 1,
						pointRadius: 5,
						pointHitRadius: 10,
						lineTension: 0
					}]
				},
				options: {
					plugins: { legend: { display: false } },
					scales: {
						yAxes: {
							ticks: {
								min: 0,
								stepSize: stepSize
							}
						}
					}
				}
			});
		}
	}

	function setHumanReadableSizeToElement(elementId) {
		var maxUploadSize = parseInt($('#' + elementId).text());

		if ($.isNumeric(maxUploadSize)) {
			$('#' + elementId).text(OC.Util.humanFileSize(maxUploadSize));
		}
	}

	function initMonitoringLinkToClipboard() {
		var monAPIBox = $("#ocsEndPoint");
		/* reused from settings/js/authtoken_view.js */
		monAPIBox.find('.clipboardButton').tooltip({placement: 'bottom', title: t('core', 'Copy'), trigger: 'hover'});

		// Clipboard!
		var clipboard = new Clipboard('.clipboardButton');
		clipboard.on('success', function (e) {
			var $input = $(e.trigger);
			$input.tooltip('hide')
				.attr('data-original-title', t('core', 'Copied!'))
				.tooltip('fixTitle')
				.tooltip({placement: 'bottom', trigger: 'manual'})
				.tooltip('show');
			_.delay(function () {
				$input.tooltip('hide')
					.attr('data-original-title', t('core', 'Copy'))
					.tooltip('fixTitle');
			}, 3000);
		});
		clipboard.on('error', function (e) {
			var $input = $(e.trigger);
			var actionMsg = '';
			if (/iPhone|iPad/i.test(navigator.userAgent)) {
				actionMsg = t('core', 'Not supported!');
			} else if (/Mac/i.test(navigator.userAgent)) {
				actionMsg = t('core', 'Press ⌘-C to copy.');
			} else {
				actionMsg = t('core', 'Press Ctrl-C to copy.');
			}

			$input.tooltip('hide')
				.attr('data-original-title', actionMsg)
				.tooltip('fixTitle')
				.tooltip({placement: 'bottom', trigger: 'manual'})
				.tooltip('show');
			_.delay(function () {
				$input.tooltip('hide')
					.attr('data-original-title', t('core', 'Copy'))
					.tooltip('fixTitle');
			}, 3000);
		});
	}

	function initServerTime() {
		var interval = 1000;  // 1000 = 1 second, 3000 = 3 seconds
		function doAjax() {
			$.ajax({
				url: OC.linkToOCS('apps/serverinfo_hetzner/api/v1/', 2) + 'basicdata?format=json',
				method: "GET",
				success: function (response) {
					var data = response.ocs.data;
					document.getElementById("servertime").innerHTML = data.servertime;
				},
				error: function (data) {
					console.log(data);
				},
				complete: function (data) {
					setTimeout(doAjax, interval);
				}
			});
		}

		setTimeout(doAjax, interval);
	}

})(jQuery, OC);
