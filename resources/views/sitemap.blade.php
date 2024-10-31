<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
	<url>
        <loc>{{env('SITEMAP_URL')}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <!-- <url>
        <loc>{{env('SITEMAP_URL').'/case-application'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{env('SITEMAP_URL').'/case-tracking'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{env('SITEMAP_URL').'/case-slip'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{env('SITEMAP_URL').'/nomminator-add'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>

    <url>
        <loc>{{env('SITEMAP_URL').'/question-answer'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{env('SITEMAP_URL').'/all-form'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{env('SITEMAP_URL').'/all-law'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{env('SITEMAP_URL').'/all-gadget'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
	<url>
        <loc>{{env('SITEMAP_URL').'/feeAll'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
	  </url>

	  <url>
        <loc>{{env('SITEMAP_URL').'/union-member'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
	  </url>

	  <url>
        <loc>{{env('SITEMAP_URL').'/all-NoticeBoard'}}</loc>
        <lastmod>{{ Carbon\Carbon::now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
	  </url> -->

   
    @foreach ($shopList as $shop)
        <url>
            <loc>{{env('SITEMAP_URL').'/shop/'.$shop->slug}}</loc>
            <lastmod>{{ $shop->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
        
    @endforeach
<!-- 
    @foreach ($dewyaniServiceList as $dewyaniServiceInfo)
        <url>
            <loc>{{env('SITEMAP_URL').'/service/'.$dewyaniServiceInfo->id.'/fouzdari'}}</loc>
            <lastmod>{{ $dewyaniServiceInfo->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
        
    @endforeach -->

    <!-- @foreach ($mediaList as $mediaInfo)
        <url>
            <loc>{{env('SITEMAP_URL').'/'.$mediaInfo->id.'/media'}}</loc>
            <lastmod>{{ $mediaInfo->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
        
    @endforeach -->

</urlset>