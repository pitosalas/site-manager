{config_load file=apache.conf}
{foreach item=c from=$classes}
{assign var=cc value=$c.class}
{if count($c.instances) gt 0}
{foreach name=i item=i from=$c.instances}
<VirtualHost *:8888>
  ServerName   {$i.name}.blogbridge.com
  DocumentRoot {#classes_dir#}/{$cc.name}
  ErrorLog     {#logs_dir#}/error_log
  CustomLog    {#logs_dir#}/access_log common
</VirtualHost>
{/foreach}
{/if}

{/foreach}
