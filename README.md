# Monitoring (Hetzner Storage Share)

**Note:** This is an adapted version of the official serverinfo app from Nextcloud! 
This customization clears (no removal) some metrics so that the app works without 
shell_exec function and it does not output sensitive system information. 

This apps provides useful server information, such as disk usage, number of users, 
etc. Check out the provided **Example XML output** for the details.

The admin can look up this information directly in Nextcloud (Settings ->
Management-> Information) or connect an external monitoring tool to the
provided end-points.

## Installation

This app is part of the default feature set of Hetzner Storage Shares.

## API

The API provides a lot of information about a running Nextcloud
instance in XML or JSON format, by using the following URL. If you want to
get the information returned in JSON format, you have to append **`?format=json`**
to the URL.
```
https://<nextcloud-fqdn>/ocs/v2.php/apps/serverinfo_hetzner/api/v1/info
```

### Example XML output:
```
<ocs>
  <meta>
    <status>ok</status>
    <statuscode>200</statuscode>
    <message>OK</message>
  </meta>
  <data>
    <nextcloud>
      <system>
        <version>15.0.4.0</version>
        <theme/>
        <enable_avatars>yes</enable_avatars>
        <enable_previews>yes</enable_previews>
        <memcache.local>OC\Memcache\APCu</memcache.local>
        <memcache.distributed>none</memcache.distributed>
        <filelocking.enabled>yes</filelocking.enabled>
        <memcache.locking>OC\Memcache\Redis</memcache.locking>
        <debug>no</debug>
        <freespace>48472801280</freespace>
        <cpuload>N/A</cpuload>
        <mem_total>8183664</mem_total>
        <mem_free>5877568</mem_free>
        <swap_total>0</swap_total>
        <swap_free>0</swap_free>
        <apps>
          <num_installed>53</num_installed>
          <num_updates_available>1</num_updates_available>
          <app_updates>
            <files_antivirus>2.0.1</files_antivirus>
          </app_updates>
        </apps>
      </system>
      <storage>
        <num_users>7</num_users>
        <num_files>708860</num_files>
        <num_storages>125</num_storages>
        <num_storages_local>7</num_storages_local>
        <num_storages_home>7</num_storages_home>
        <num_storages_other>111</num_storages_other>
      </storage>
      <shares>
        <num_shares>1</num_shares>
        <num_shares_user>0</num_shares_user>
        <num_shares_groups>0</num_shares_groups>
        <num_shares_link>0</num_shares_link>
        <num_shares_link_no_password>0</num_shares_link_no_password>
        <num_fed_shares_sent>0</num_fed_shares_sent>
        <num_fed_shares_received>0</num_fed_shares_received>
        <permissions_4_1>1</permissions_4_1>
      </shares>
    </nextcloud>
    <server>
      <webserver>Apache</webserver>
      <php>
        <version>7.2</version>
        <memory_limit>536870912</memory_limit>
        <max_execution_time>3600</max_execution_time>
        <upload_max_filesize>535822336</upload_max_filesize>
      </php>
      <database>
        <type>mysql</type>
        <version>10.2</version>
        <size>331382784</size>
      </database>
    </server>
    <activeUsers>
      <last5minutes>2</last5minutes>
      <last1hour>4</last1hour>
      <last24hours>5</last24hours>
    </activeUsers>
  </data>
</ocs>
```

### Example JSON output:
```
{"ocs":{"meta":{"status":"ok","statuscode":200,"message":"OK"},"data":{"nextcloud":{"system":{"version":"15.0.4.0","theme":"","enable_avatars":"yes","enable_previews":"yes","memcache.local":"OC\\Memcache\\APCu","memcache.distributed":"none","filelocking.enabled":"yes","memcache.locking":"OC\\Memcache\\Redis","debug":"no","freespace":48472944640,"cpuload":"N\/A","mem_total":"N\/A","mem_free":"N\/A","swap_total":"N\/A","swap_free":"N\/A","apps":{"num_installed":53,"num_updates_available":1,"app_updates":{"files_antivirus":"2.0.1"}}},"storage":{"num_users":7,"num_files":708860,"num_storages":125,"num_storages_local":7,"num_storages_home":7,"num_storages_other":111},"shares":{"num_shares":1,"num_shares_user":0,"num_shares_groups":0,"num_shares_link":0,"num_shares_link_no_password":0,"num_fed_shares_sent":0,"num_fed_shares_received":0,"permissions_4_1":"1"}},"server":{"webserver":"Apache","php":{"version":"7.2","memory_limit":536870912,"max_execution_time":3600,"upload_max_filesize":535822336},"database":{"type":"mysql","version":"10.2","size":331382784}},"activeUsers":{"last5minutes":2,"last1hour":3,"last24hours":5}}}}
```

## Configuration

##### Background job to update storage statistics

Since collecting storage statistics might take time and cause slow downs, they are updated in the background. A background job runs once every three hours to update the number of storages and files. The interval can be overridden per app settings (the value is specified in seconds):

``php occ config:app:set --value=3600 serverinfo_hetzner job_interval_storage_stats``

It is also possible to trigger the update manually per occ call. With verbose mode enabled, the current values are being printed.

```
php occ serverinfo_hetzner:update-storage-statistics -v --output=json_pretty
{
    "num_users": 80,
    "num_files": 3934,
    "num_storages": 2545,
    "num_storages_local": 2,
    "num_storages_home": 2510,
    "num_storages_other": 33
}
```

##### Restricted mode (>= Nextcloud 28)

To obtain information about your server, the serverinfo app reads files outside the application directory (e.g. /proc on Linux) or executes shell commands (e.g. df on Linux). 

If you don't want that (for example, to avoid open_basedir warnings) enable the restricted mode.

Enable:

``php occ config:app:set --value=yes serverinfo restricted_mode``

Disable:

``php occ config:app:delete serverinfo restricted_mode``

##### Show phpinfo (>= Nextcloud 28)

Enable:

``php occ config:app:set --value=yes serverinfo phpinfo``

Disable:

``php occ config:app:delete serverinfo phpinfo``
