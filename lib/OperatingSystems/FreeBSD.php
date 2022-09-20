<?php

declare(strict_types=1);

/**
 * @author Matthew Wener <matthew@wener.org>
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
 * along with this program.  If not, see <https://www.gnu.org/licenses/>
 *
 */

namespace OCA\ServerInfoHetzner\OperatingSystems;

use OCA\ServerInfoHetzner\Resources\Memory;

class FreeBSD implements IOperatingSystem {
	public function supported(): bool {
		return false;
	}

	public function getMemory(): Memory {
        return new Memory();
	}

	public function getCpuName(): string {
		return 'N/A';
	}

	public function getTime(): string {
        return date(\DateTimeInterface::RFC7231);
	}

	public function getUptime(): int {
		return -1;
	}

	public function getNetworkInfo(): array {
        $result = [];
        $result['hostname'] = 'N/A';
        $result['dns'] = 'N/A';
        $result['gateway'] = 'N/A';
        return $result;
	}

	public function getNetworkInterfaces(): array {
		return [];
	}

	public function getDiskInfo(): array {
		return [];
	}

	public function getThermalZones(): array {
		return [];
	}

	protected function executeCommand(string $command): string {
		$output = @shell_exec(escapeshellcmd($command));
		if ($output === null || $output === '' || $output === false) {
			throw new \RuntimeException('No output for command: "' . $command . '"');
		}
		return $output;
	}
}
