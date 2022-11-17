<?php

declare(strict_types=1);

/**
 * @author Frank Karlitschek <frank@nextcloud.com>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\ServerInfoHetzner;

use OCA\ServerInfoHetzner\OperatingSystems\DefaultOs;
use OCA\ServerInfoHetzner\OperatingSystems\FreeBSD;
use OCA\ServerInfoHetzner\OperatingSystems\IOperatingSystem;
use OCA\ServerInfoHetzner\Resources\Memory;

class Os implements IOperatingSystem {
	protected IOperatingSystem $backend;

	/**
	 * Os constructor.
	 */
	public function __construct() {
		if (PHP_OS === 'FreeBSD') {
			$this->backend = new FreeBSD();
		} else {
			$this->backend = new DefaultOs();
		}
	}

	public function supported(): bool {
		$data = $this->backend->supported();
		return $data;
	}

	public function getHostname(): string {
		return 'N/A';
	}

	/**
	 * Get name of the operating system.
	 */
	public function getOSName(): string {
		return 'N/A';
	}

	public function getMemory(): Memory {
		return $this->backend->getMemory();
	}

	public function getCpuName(): string {
		return $this->backend->getCpuName();
	}

	public function getTime(): string {
		return $this->backend->getTime();
	}

	public function getUptime(): int {
		return $this->backend->getUptime();
	}

	public function getDiskInfo(): array {
		return $this->backend->getDiskInfo();
	}

	/**
	 * Get diskdata will return a numerical list with two elements for each disk (used and available) where all values are in gigabyte.
	 * [
	 *        [used => 0, available => 0],
	 *        [used => 0, available => 0],
	 * ]
	 *
	 * @return array<array-key, array>
	 */
	public function getDiskData(): array {
		$data = [];

		foreach ($this->backend->getDiskInfo() as $disk) {
			$data[] = [
				round($disk->getUsed() / 1024 , 1),
				round($disk->getAvailable() / 1024, 1)
			];
		}

		return $data;
	}

	public function getNetworkInfo(): array {
		$data = $this->backend->getNetworkInfo();
		return $data;
	}

	public function getNetworkInterfaces(): array {
		$data = $this->backend->getNetworkInterfaces();
		return $data;
	}

	public function getThermalZones(): array {
		$data = $this->backend->getThermalZones();
		return $data;
	}
}
