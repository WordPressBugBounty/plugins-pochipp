/**
 * @WordPress dependencies
 */
import { memo } from '@wordpress/element';
import { hasFilter } from '@wordpress/hooks';
import ServerSideRender from '@wordpress/server-side-render';

// 価格の表示を行う判定を取得
const getDispPrice = (isShowCommonSetting, isShowIndivisualSetting, isHideIndivisualSetting) => {
	if (isShowIndivisualSetting) {
		return true;
	}
	if (isHideIndivisualSetting) {
		return false;
	}
	return isShowCommonSetting;
};

/**
 * ItemPreview
 */
export default memo(({ postId, postTitle, customImgUrl, parsedMeta }) => {
	// 投稿タイトルもmeta情報も持たないとき。
	if (!postTitle && 0 === Object.keys(parsedMeta).length) {
		return (
			<div className='__preview -null'>
				<p>商品を選択してください</p>
			</div>
		);
	}

	const {
		asin,
		info,
		price,
		showPrice,
		hidePrice,
		hideReviewUrl,
		image_url: imageUrl,
		price_at: priceAt,
		searched_at: searchedAt,
	} = parsedMeta;

	// ポチップ設定データ
	const pchppVars = window.pchppVars || {};

	let dataBtnStyle = pchppVars.btnStyle || 'dflt';
	if ('default' === dataBtnStyle) dataBtnStyle = 'dflt';

	// 価格表示
	const dispPrice = getDispPrice(pchppVars.displayPrice !== 'off', showPrice, hidePrice);

	// レビューリンク表示
	const dispReviewLink = hasFilter('pochipp.exReviewCheckbox', 'pochipp-pro') && !!asin && !hideReviewUrl;

	return (
		<div className='__preview'>
			<div
				className='pochipp-box'
				data-img={pchppVars.imgPosition || 'l'}
				data-lyt-pc={pchppVars.boxLayoutPC || 'dflt'}
				data-lyt-mb={pchppVars.boxLayoutMB || 'vrtcl'}
				data-btn-style={dataBtnStyle}
				data-btn-radius={pchppVars.btnRadius || 'off'}
			>
				<div className='pochipp-box__image'>
					<img src={customImgUrl || imageUrl} alt='' />
				</div>
				<div className='pochipp-box__body'>
					<div className='pochipp-box__title'>{postTitle}</div>
					{info && <div className='pochipp-box__info'>{info}</div>}
					{price && dispPrice && (
						<div className='pochipp-box__price'>
							¥{price.toLocaleString()}
							<span>
								（{priceAt}時点 | {searchedAt}調べ）
							</span>
						</div>
					)}
					{dispReviewLink && (
						<div className='pochipp-box__review'>
							<span className='dashicons dashicons-format-chat'></span>
							<span>口コミを見る</span>
						</div>
					)}
					<ServerSideRender
						block='pochipp/setting-preview'
						attributes={{ meta: JSON.stringify({ pid: postId, ...parsedMeta }) }}
						className={`_components-disabled`}
					/>
				</div>
			</div>
			<div className='__helpText -with-margin'>※ プレビュー内のボタンはアフィリエイトリンク化されていません。</div>
			{hasFilter('pochipp.exReviewCheckbox', 'pochipp-pro') && (
				<div className='__helpText'>※ レビューリンクは検索元がAmazonの場合にのみ、表示されます。</div>
			)}
		</div>
	);
});
