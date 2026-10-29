<?php
// MCP config. Is file ko public_html ke ROOT me rakho (public folder ke BAHAR, .env ke saath).
// Secret kisi ko share mat karna. Badalna ho to yahan badlo aur Claude connector ka URL bhi update karo.
return [
    'secret'     => '390dd603bfd12b531e31849bbe50cd76ee1fbd5284ea2ee0',
    'site_url'   => 'https://mjcheezain.com',
    'upload_ttl' => 21600, // upload link kitni der valid rahe (seconds) = 6 ghante
    'audit'      => true,  // har write query ka log: public_html/mcp-audit.log
];
