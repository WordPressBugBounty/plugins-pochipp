<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="pchpp-setting__section_help">
	<p>
		※ ご利用前にAmazonの公式ドキュメントをご確認ください。<br>
		<a href="https://affiliate.amazon.co.jp/help/operating/paapilicenseagreement" target="_blank" rel="noopener noreferrer">Amazon.co.jp API ライセンス契約</a>
		/
		<a href="https://affiliate.amazon.co.jp/help/operating/agreement" target="_blank" rel="noopener noreferrer">Amazonアソシエイト・プログラム運営規約</a>
		/
		<a href="https://affiliate.amazon.co.jp/help/node" target="_blank" rel="noopener noreferrer">Amazonアソシエイトに関するヘルプページ</a>
	</p>
	<div class="__helpLink">
		Amazon APIの設定方法は<a href="https://pochipp.com/200/" target="_blank" rel="noopener noreferrer" class="dashicons-before dashicons-book-alt">こちらのページ</a>で解説しています。
	</div>
</div>

<?php
// PA-API設定値をhiddenで保持（UI非表示）
\POCHIPP::output_hidden(['key' => 'amazon_access_key']);
\POCHIPP::output_hidden(['key' => 'amazon_secret_key']);
?>

<h3 class="pchpp-setting__h3">Creators API設定</h3>
<p class="pchpp-setting__p">
	認証情報は「<a href="https://affiliate.amazon.co.jp/creatorsapi" target="_blank" rel="noopener noreferrer">Creators API</a>」のページから取得できます。
	<br>
	アプリケーションを作成の上で、「認証情報ID」と「シークレット」を設定してください。
	<br>
</p>
<div class="pchpp-setting__div amazon-creators">
	<dl class="pchpp-setting__dl">
		<dt>認証情報ID</dt>
		<dd>
			<?php
				\POCHIPP::output_text_field([
					'key' => 'amazon_creators_client_id',
				]);
			?>
			<span class="errMessage"></span>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>シークレット</dt>
		<dd>
			<?php
				\POCHIPP::output_text_field([
					'key' => 'amazon_creators_client_secret',
				]);
			?>
			<span class="errMessage"></span>
		</dd>
	</dl>
</div>

<h3 class="pchpp-setting__h3">アフィリエイト設定</h3>
<p class="pchpp-setting__p">
	Amazonアソシエイトの「トラッキングID」を設定することで、商品リンクがアフィリエイトリンクに自動変換されます。
	<br>
	利用できるIDは<a href="https://affiliate.amazon.co.jp/home/account/tag/manage" target="_blank" rel="noopener noreferrer">トラッキングIDの管理</a>から確認できます。
</p>
<div class="pchpp-setting__div amazon-affiliate">
	<dl class="pchpp-setting__dl">
		<dt>トラッキングID</dt>
		<dd>
			<?php
				\POCHIPP::output_text_field([
					'key'         => 'amazon_traccking_id',
				]);
			?>
			<span class="errMessage"></span>
		</dd>
	</dl>
</div>
