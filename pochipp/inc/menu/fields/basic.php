<?php
namespace POCHIPP;

if ( ! defined( 'ABSPATH' ) ) exit;

$btn_style = \POCHIPP::get_setting( 'btn_style' );
	if ( 'default' === $btn_style ) {
	$btn_style = 'dflt';
	}
?>
<div class="pchpp-setting__section_help">
	<div class="__helpLink">
		Pochippの初期設定方法は<a href="https://pochipp.com/5428/" target="_blank" rel="noopener noreferrer" class="dashicons-before dashicons-book-alt">こちらのページ</a>で解説しています。
	</div>
</div>

<h3 class="pchpp-setting__h3">ボックスのデザイン設定</h3>
<div class="pchpp-setting__div">
	<div class="pchpp-setting__preview">
		<div class="__wrap">
			<!-- <div class="__label">プレビュー</div> -->
			<div class="__inner">
				<div class="pochipp-box"
					data-img="<?php echo esc_attr( \POCHIPP::get_setting( 'img_position' ) ); ?>"
					data-lyt-pc="<?php echo esc_attr( \POCHIPP::get_setting( 'box_layout_pc' ) ); ?>"
					data-lyt-mb="<?php echo esc_attr( \POCHIPP::get_setting( 'box_layout_mb' ) ); ?>"
					data-btn-style="<?php echo esc_attr( $btn_style ); ?>"
					data-btn-radius="<?php echo esc_attr( \POCHIPP::get_setting( 'btn_radius' ) ); ?>"
					data-sale-effect="<?php echo esc_attr( \POCHIPP::get_setting( 'sale_text_effect' ) ); ?>"
				>
					<div class="pochipp-box__image">
						<a href="###" rel="nofollow">
							<img src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/box_preview_img.png" alt="">
						</a>
					</div>
					<div class="pochipp-box__body">
						<div class="pochipp-box__title">
							<a href="###" rel="nofollow">
							Lorem Ipsum Watch 商品タイトル 腕時計 ABC-Z3 最新 防水 ソーラー</a>
						</div>
						<div class="pochipp-box__info">Lorem Ipsum</div>
						<div
							data-disp-price="<?php echo esc_attr( \POCHIPP::get_setting( 'display_price' ) ); ?>"
							class="pochipp-box__price"
						>¥10,000 <span>（2021/01/01 11:11時点 | 〇〇調べ）</span></div>
					</div>
					<div class="pochipp-box__btns"
						data-maxclmn-pc="<?php echo esc_attr( \POCHIPP::get_setting( 'max_column_pc' ) ); ?>"
						data-maxclmn-mb="<?php echo esc_attr( \POCHIPP::get_setting( 'max_column_mb' ) ); ?>"
					>
						<div class="pochipp-box__btnwrap -amazon">
							<a href="###" class="pochipp-box__btn" rel="nofollow">Amazon</a>
						</div>
						<div class="pochipp-box__btnwrap -rakuten">
							<a href="###" class="pochipp-box__btn" rel="nofollow">楽天市場</a>
						</div>
						<div class="pochipp-box__btnwrap -yahoo">
							<a href="###" class="pochipp-box__btn" rel="nofollow">Yahooショッピング</a>
						</div>
						<div class="pochipp-box__btnwrap -mercari">
							<a href="###" class="pochipp-box__btn" rel="nofollow">メルカリ</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<dl class="pchpp-setting__dl">
		<dt>レイアウト（PC）</dt>
		<dd>
			<?php
				\POCHIPP::output_radio([
					'key'     => 'box_layout_pc',
					'class'   => '-flex',
					'choices' => [
						'dflt'   => '標準',
						'big'    => 'ビッグ',
						'imgbig' => '画像ビッグ',
					],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>レイアウト（モバイル）</dt>
		<dd>
			<?php
				\POCHIPP::output_radio([
					'key'     => 'box_layout_mb',
					'class'   => '-flex',
					'choices' => [
						'vrtcl' => '縦並び',
						'flex'  => '画像とタイトル横並び',
					],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>画像の配置</dt>
		<dd>
			<?php
				\POCHIPP::output_radio([
					'key'     => 'img_position',
					'class'   => '-flex',
					'choices' => [
						'l' => '左',
						'r' => '右',
					],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>ボタンスタイル</dt>
		<dd>
			<?php
				\POCHIPP::output_radio([
					'key'     => 'btn_style',
					'class'   => '-flex',
					'choices' => [
						'dflt'    => '標準',
						'outline' => 'アウトライン',
					],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>ボタンの丸み</dt>
		<dd>
			<?php
				\POCHIPP::output_radio([
					'key'     => 'btn_radius',
					'class'   => '-flex',
					'choices' => [
						'off' => '四角',
						'on'  => '丸め',
					],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>ボタン幅（PC）</dt>
		<dd>
			<?php
				\POCHIPP::output_radio([
					'key'     => 'max_column_pc',
					'class'   => '-flex',
					'choices' => [
						'fit'     => '自動フィット',
						'text'    => 'テキストに応じる',
						'3'       => '3列幅',
						'2'       => '2列幅',
					],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>ボタン幅（モバイル）</dt>
		<dd>
			<?php
				\POCHIPP::output_radio([
					'key'     => 'max_column_mb',
					'class'   => '-flex',
					'choices' => [
						'1'  => '1列幅',
						'2'  => '2列幅 <small>（※ セール情報表示中のボタンは1列幅に広がります。）</small>',
					],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>商品の価格表示</dt>
		<dd>
			<?php
			\POCHIPP::output_radio([
				'key'     => 'display_price',
				'class'   => '-flex',
				'choices' => [
					'on'  => '表示',
					'off' => '非表示',
				],
			]);
			?>
		</dd>
	</dl>
</div>

<h3 class="pchpp-setting__h3">インラインボタンのデザイン設定</h3>
<div class="pchpp-setting__div">
	<div class="pchpp-inline-setting__preview">
		<div class="__wrap">
			<!-- <div class="__label">プレビュー</div> -->
			<table class="comparison-chart-table">
				<tbody>
					<tr>
						<td>商品</td>
						<td>
							<img src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/inline_preview_1.jpg"  alt="" width="200">
						</td>
						<td>
							<img src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/inline_preview_2.jpg"  alt="" width="200">
						</td>
						<td>
							<img src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/inline_preview_3.jpg"  alt="" width="200">
						</td>
						<td>
							<img src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/inline_preview_4.jpg"  alt="" width="200">
						</td>
					</tr>
					<tr>
						<td>価格</td>
						<td class="-center">2,000円</td>
						<td class="-center">2,500円</td>
						<td class="-center">2,200円</td>
						<td class="-center">1,980円</td>
					</tr>
					<tr>
						<td>リンク</td>
						<td>
							<span
								class="pochipp-inline__btnwrap -amazon -inline"
								data-inline-btn-width="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_width' ) ); ?>"
								data-inline-btn-style="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_style' ) ); ?>"
								data-inline-btn-radius="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_radius' ) ); ?>"
							>
								<a href="###" class="pochipp-inline__btn">Amazon</a>
								<img src="###" width="1" height="1" style="border:none;" alt="">
							</span>
						</td>
						<td>
							<span
								class="pochipp-inline__btnwrap -rakuten -inline"
								data-inline-btn-width="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_width' ) ); ?>"
								data-inline-btn-style="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_style' ) ); ?>"
								data-inline-btn-radius="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_radius' ) ); ?>"
							>
								<a href="###" class="pochipp-inline__btn">楽天</a>
								<img src="###" width="1" height="1" style="border:none;" alt="">
							</span>
						</td>
						<td>
							<span
								class="pochipp-inline__btnwrap -yahoo -inline"
								data-inline-btn-width="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_width' ) ); ?>"
								data-inline-btn-style="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_style' ) ); ?>"
								data-inline-btn-radius="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_radius' ) ); ?>"
							>
								<a href="###" class="pochipp-inline__btn">Yahooショッピング</a>
								<img src="###" width="1" height="1" style="border:none;" alt="">
							</span>
						</td>
						<td>
							<span
								class="pochipp-inline__btnwrap -mercari -inline"
								data-inline-btn-width="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_width' ) ); ?>"
								data-inline-btn-style="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_style' ) ); ?>"
								data-inline-btn-radius="<?php echo esc_attr( \POCHIPP::get_setting( 'inline_btn_radius' ) ); ?>"
							>
								<a href="###" class="pochipp-inline__btn">メルカリ</a>
								<img src="###" width="1" height="1" style="border:none;" alt="">
							</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<dl class="pchpp-setting__dl">
		<dt>ボタンスタイル</dt>
		<dd>
			<?php
			\POCHIPP::output_radio([
				'key'     => 'inline_btn_style',
				'class'   => '-flex',
				'choices' => [
					'dflt'    => '標準',
					'outline' => 'アウトライン',
				],
			]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>ボタンの丸み</dt>
		<dd>
			<?php
			\POCHIPP::output_radio([
				'key'     => 'inline_btn_radius',
				'class'   => '-flex',
				'choices' => [
					'off' => '四角',
					'on'  => '丸め',
				],
			]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>ボタン幅</dt>
		<dd>
			<?php
			\POCHIPP::output_radio([
				'key'     => 'inline_btn_width',
				'class'   => '-flex',
				'choices' => [
					'text'      => 'テキストに応じる',
					'small_fix' => '固定幅（狭）',
					'fix'       => '固定幅',
					'wide_fix'  => '固定幅（幅広）',
				],
			]);
			?>
		</dd>
	</dl>
</div>

<h3 class="pchpp-setting__h3">各ボタンの表示テキスト</h3>
<!-- <p class="pchpp-setting__p"></p> -->
<div class="pchpp-setting__div">
	<dl class="pchpp-setting__dl">
		<dt>Amazonボタン</dt>
		<dd>
			<?php
				\POCHIPP::output_text_field([
					'key' => 'amazon_btn_text',
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>楽天市場ボタン</dt>
		<dd>
			<?php
				\POCHIPP::output_text_field([
					'key' => 'rakuten_btn_text',
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>Yahooショッピングボタン</dt>
		<dd>
			<?php
				\POCHIPP::output_text_field([
					'key' => 'yahoo_btn_text',
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>メルカリボタン</dt>
		<dd>
			<?php
				\POCHIPP::output_text_field([
					'key' => 'mercari_btn_text',
				]);
			?>
		</dd>
	</dl>
</div>


<h3 class="pchpp-setting__h3">各ボタンの色</h3>
<!-- <p class="pchpp-setting__p"></p> -->
<div class="pchpp-setting__div">
	<dl class="pchpp-setting__dl">
		<dt>Amazon</dt>
		<dd>
			<?php
			\POCHIPP::output_colorpicker([
				'key'     => 'amazon_btn_color',
				'default' => \Pochipp::$default_data['amazon_btn_color'],
			]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>楽天</dt>
		<dd>
			<?php
			\POCHIPP::output_colorpicker([
				'key'     => 'rakuten_btn_color',
				'default' => \Pochipp::$default_data['rakuten_btn_color'],
			]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>Yahooショッピング</dt>
		<dd>
			<?php
			\POCHIPP::output_colorpicker([
				'key'     => 'yahoo_btn_color',
				'default' => \Pochipp::$default_data['yahoo_btn_color'],
			]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>メルカリ</dt>
		<dd>
			<?php
			\POCHIPP::output_colorpicker([
				'key'     => 'mercari_btn_color',
				'default' => \Pochipp::$default_data['mercari_btn_color'],
			]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>カスタムボタン1</dt>
		<dd>
			<?php
				\POCHIPP::output_colorpicker([
					'key'     => 'custom_btn_color',
					'default' => \Pochipp::$default_data['custom_btn_color'],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>カスタムボタン2</dt>
		<dd>
			<?php
				\POCHIPP::output_colorpicker([
					'key'     => 'custom_btn_color_2',
					'default' => \Pochipp::$default_data['custom_btn_color_2'],
				]);
			?>
		</dd>
	</dl>
	<dl class="pchpp-setting__dl">
		<dt>インラインボタン</dt>
		<dd>
			<?php
				\POCHIPP::output_colorpicker([
					'key'     => 'inline_btn_color',
					'default' => \Pochipp::$default_data['inline_btn_color'],
				]);
			?>
		</dd>
	</dl>
</div>


<h3 class="pchpp-setting__h3">ボタンのリンクターゲット</h3>
<!-- <p class="pchpp-setting__p"></p> -->
<div class="pchpp-setting__div">
	<dl class="pchpp-setting__dl">
		<!-- <dt></dt> -->
		<dd>
			<?php
				\POCHIPP::output_checkbox([
					'key'   => 'show_amazon_normal_link',
					'label' => 'リンク先を別ウィンドウで開く',
				]);
			?>
			<p class="pchpp-setting__desc">
				チェックをオンにすると、各ボタンに<code>target="_blank"</code>がつきます。
			</p>
		</dd>
	</dl>
</div>


<h3 class="pchpp-setting__h3">商品情報の定期更新・リンク切れチェック</h3>
<div class="pchpp-setting__div">
	<dl class="pchpp-setting__dl">
		<dd>
			<?php
				\POCHIPP::output_checkbox([
					'key'   => 'auto_update',
					'label' => '商品情報を自動更新する',
				]);
			?>
			<p class="pchpp-setting__desc">
				チェックをオンにすると、定期的（約1週間ごと）に商品情報を更新します。
			</p>
			<p class="pchpp-setting__desc">
				<small>※ 更新される情報は 価格・画像・商品URL のみです。</small><br>
				<small>※ 更新対象の商品は、ポチップ管理に登録済みかつAmazon API・楽天APIのいずれかで検索された商品のみです。</small><br>
				<small>※ リンク切れチェックは検索元の商品のみ行われます。</small>
			</p>
		</dd>
	</dl>
</div>
