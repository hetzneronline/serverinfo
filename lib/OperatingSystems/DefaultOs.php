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

namespace OCA\ServerInfoHetzner\OperatingSystems;

use OCA\ServerInfoHetzner\Resources\Memory;

class DefaultOs implements IOperatingSystem {

	/**
	 * @return bool
	 */
	public function supported(): bool {
		return true;
	}

	public function getMemory(): Memory {
		return new Memory();
	}

	public function getCpuName(): string {
		return 'N/A';
	}

	/**
	 * @return string
	 */
	public function getTime(): string {
		return date(\DateTimeInterface::RFC7231);
	}

	public function getUptime(): int {
		return -1;
	}

	/**
	 * @return array
	 */
	public function getNetworkInfo(): array {
		$result = [];
		$result['hostname'] = 'N/A';
		$result['dns'] = 'N/A';
		$result['gateway'] = 'N/A';
		return $result;
	}

	/**
	 * @return array
	 */
	public function getNetworkInterfaces(): array {
		return [];
	}

	public function getDiskInfo(): array {
		return [];
	}
}
