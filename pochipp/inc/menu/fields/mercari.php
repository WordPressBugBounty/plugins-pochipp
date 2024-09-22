<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

$now_tab          = \POCHIPP::get_sanitized_data( $_GET, 'tab', 'text', 'basic' );
$registerd_items  = [];
$shop_items       = [];
$current_settings = [];

if ( 'mercari' === $now_tab ) {
	// 登録済み商品
	$registerd_items = \POCHIPP::get_registerd_items( [
		'count' => -1,
	] );
} else {
	$current_settings = \POCHIPP::get_setting( 'mercari_hidden_settings' ) ?: [];
}
foreach ( $registerd_items as $item ) {
	if ( ( $item['searched_at'] ?? '' ) === 'simple' ) continue;
	array_push( $shop_items, $item );
}
?>

<div class="pchpp-setting__section_help">
	<p>
		※ ご利用前にメルカリアンバサダーの公式ドキュメントをご確認ください。<br>
		<a href="https://help.jp.mercari.com/guide/articles/1486" target="_blank" rel="noopener noreferrer">メルカリアンバサダーご利用ガイドライン</a>
	</p>
	<div class="__helpLink">
		メルカリアンバサダーのIDの設定方法は<a href="https://pochipp.com/5961/" target="_blank" rel="noopener noreferrer" class="dashicons-before dashicons-book-alt">こちらのページ</a>で解説しています。
	</div>
</div>
<h3 class="pchpp-setting__h3">アフィリエイト設定</h3>
<p class="pchpp-setting__p">
	メルカリアンバサダーの「あなたのID」を設定することで、商品リンクがアフィリエイトリンクに自動変換されます。
	<br>
	利用できるIDは<a href="https://jp.mercari.com/ambassador/dashboard" target="_blank" rel="noopener noreferrer">ダッシュボード</a>から確認できます。
</p>
<div class="pchpp-setting__div mercari">
	<dl class="pchpp-setting__dl">
		<dt>あなたのID</dt>
		<dd>
			<?php
				\POCHIPP::output_text_field([
					'key' => 'mercari_ambassador_id',
				]);
			?>
			<span class="errMessage"></span>
		</dd>
	</dl>
</div>
<h3 class="pchpp-setting__h3">リンク非表示設定</h3>
<p class="pchpp-setting__p">
	選択した商品はメルカリのリンクが非表示になります。
</p>
<button data-mercari-modal-open type="button" class="button">商品情報を検索する</button>
<p class="pchpp-setting__p-caption">
	<span data-selected-items>0</span>件の商品を選択済み
</p>

<!-- ここから商品選択モーダル -->
<div id="mercari-modal" class="pchpp-hide-modal -hidden">
	<div class="pchpp-hide-modal__background"></div>
	<div class="pchpp-hide-modal__body">
		<div class="pchpp-hide-modal__content">
			<div class="pchpp-hide-modal__header">
				<div class="pchpp-hide-modal__title">
					<div></div>
					<span>商品を追加</span>
					<a href="javascript:void(0)" class="dashicons dashicons-no" data-mercari-modal-close></a>
				</div>
				<div class="pchpp-hide-modal__search">
					<input type="text" placeholder="商品を検索する" data-search-items />
				</div>
			</div>
			<div class="pchpp-hide-modal__linkWrapper pchpp-setting__div">
				<dl class="pchpp-setting__dl <?php if ( count( $shop_items ) > 0 ) echo '-hidden'; ?>" data-noitem>
					<span>対象の商品情報が存在しません。リンクの追加は「ポチップ管理」から行えます。</span>
					<?php
						foreach ( $current_settings as $key => $value ) {
							\POCHIPP::output_hidden([
								'key'        => 'mercari_hidden_settings',
								'nested_key' => $key,
								'val'        => $value,
							]);
						}
					?>
				</dl>
				<?php foreach ( $shop_items as $item ) : ?>
						<dl class="pchpp-setting__dl -border">
						<?php
						\POCHIPP::output_checkbox_list([
							'key'       => 'mercari_hidden_settings',
							'id'        => $item['post_id'],
							'label'     => $item['title'],
							'image_url' => $item['image_url'] ?? '',
						]);
						?>
						</dl>
				<?php endforeach; ?>
			</div>
			<div class="pchpp-hide-modal__footer">
				<button data-mercari-modal-close class="button button-primary" type="button">選択を終了</button>
			</div>
		</div>
	</div>
</div>
