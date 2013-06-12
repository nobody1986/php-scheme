<?php
/**

  <urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
  <url>
  <loc>http://homepage.yesky.com</loc>
  <lastmod>2005-06-03T04:20-08:00</lastmod>
  <changefreq>always</changefreq>
  <priority>1.0</priority>
  </url>
  <url>
  <loc>http://homepage.yesky.com/300687.html</loc>
  <lastmod>2005-06-02T20:20:36Z</lastmod>
  <changefreq>daily</changefreq>
  <priority>0.8</priority>
  </url>
  </urlset>
 */
$db = new PDO('mysql:host=127.0.0.1;dbname=12pir2', 'root', 'iamsnow', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"'));
$sql = 'SELECT * FROM ch_article  order by update_time desc';
$stmt = $db->prepare($sql);
$stmt->execute();

$xml = '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">';
while ($row = $stmt->fetch()) {
    $row['update_time_format']= date('c',$row['update_time']);
    $xml .= "<url>
  <loc>http://www.12pir2.com/article/view/id/{$row['id']}</loc>
  <lastmod>{$row['update_time_format']}</lastmod>
  <changefreq>daily</changefreq>
  <priority>0.8</priority>
  </url>";
}
$xml .= '</urlset>';
echo $xml;