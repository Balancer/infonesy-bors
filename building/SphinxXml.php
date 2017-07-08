<?php

namespace Infonesy\Search;

class SphinxXml
{
	var $result = [];

	function reg($object)
	{
/*
<?xml version="1.0" encoding="utf-8"?>
<sphinx:docset>
        <sphinx:document id="1">
                <from_>Balancer</from_>
                <to>Shumerka</to>
                <subject>Hello</subject>
                <is_deleted>0</is_deleted>
            </sphinx:document>

</sphinx:docset>
*/

		$data = [
			'is_deleted' => 0,
		];

		if($kws = $object->keywords())
			$data['keywords'] = join(', ', $kws);
		else
			$data['keywords'] = '';

		$data_xml = [];
		foreach($data as $key => $value)
			$data_xml[] = "\t\t<$key>".htmlspecialchars($value)."</$key>";

		$data_xml = join("\n", $data_xml);

		$record = "
	<sphinx:document id=\"".crc64($object->infonesy_uuid(), '%u')."\">
		<title>".htmlspecialchars($object->title())."</title>
		<description>".htmlspecialchars($object->description())."</description>
		<text>".htmlspecialchars($object->text())."</text>
		<infonesy_uuid>".htmlspecialchars($object->infonesy_uuid())."</infonesy_uuid>
		<infonesy_node_uuid>".htmlspecialchars($object->infonesy_node()->infonesy_uuid())."</infonesy_node_uuid>
		<infonesy_node_url>".htmlspecialchars($object->infonesy_node()->url())."</infonesy_node_url>
		<create_time>".htmlspecialchars($object->create_time())."</create_time>
		<modify_time>".htmlspecialchars($object->modify_time())."</modify_time>
		<author_uuid>".htmlspecialchars($object->author()->infonesy_uuid())."</author_uuid>
		<author_url>".htmlspecialchars($object->author()->url())."</author_url>
		<author_title>".htmlspecialchars($object->author()->title())."</author_title>
		<url>".htmlspecialchars($object->url())."</url>
$data_xml
	</sphinx:document>
";
		$this->result[] = $record;
	}

	function dump($file = NULL)
	{
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<sphinx:docset>"
.join("", $this->result).
"</sphinx:docset>";

		file_put_contents($file, $xml);
	}
}


/**
* @return array
*/
function crc64Table()
{
    $crc64tab = [];

    // ECMA polynomial
    $poly64rev = (0xC96C5795 << 32) | 0xD7870F42;

    // ISO polynomial
    // $poly64rev = (0xD8 << 56);

    for ($i = 0; $i < 256; $i++)
    {
        for ($part = $i, $bit = 0; $bit < 8; $bit++) {
            if ($part & 1) {
                $part = (($part >> 1) & ~(0x8 << 60)) ^ $poly64rev;
            } else {
                $part = ($part >> 1) & ~(0x8 << 60);
            }
        }

       $crc64tab[$i] = $part;
    }

    return $crc64tab;
}

/**
* @param string $string
* @param string $format
* @return mixed
* 
* Formats:
*  crc64('php'); // afe4e823e7cef190
*  crc64('php', '0x%x'); // 0xafe4e823e7cef190
*  crc64('php', '0x%X'); // 0xAFE4E823E7CEF190
*  crc64('php', '%d'); // -5772233581471534704 signed int
*  crc64('php', '%u'); // 12674510492238016912 unsigned int
*/
function crc64($string, $format = '%x')
{
    static $crc64tab;

    if ($crc64tab === null) {
        $crc64tab = crc64Table();
    }

    $crc = 0;

    for ($i = 0; $i < strlen($string); $i++) {
        $crc = $crc64tab[($crc ^ ord($string[$i])) & 0xff] ^ (($crc >> 8) & ~(0xff << 56));
    }

    return sprintf($format, $crc);
}
