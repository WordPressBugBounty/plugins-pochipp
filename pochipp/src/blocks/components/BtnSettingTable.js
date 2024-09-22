/**
 * @WordPress dependencies
 */
import { memo } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { Icon, external, search, closeSmall } from '@wordpress/icons';
import { genSerchedResultLink } from '@blocks/helper';

const labels = {
	amazon: 'Amazon',
	rakuten: '楽天',
	yahoo: 'Yahoo',
	mercari: 'メルカリ',
};

/**
 * UrlConfBtn
 */
const UrlConfBtn = memo(({ url, isProductPage }) => {
	return (
		<span className='__urlConfBtn'>
			<span>リンク先 : </span>
			<a href={url} target='_blank' rel='noreferrer noopener'>
				{isProductPage ? '商品ページ' : '検索結果ページ'}
				<Icon icon={external} />
			</a>
		</span>
	);
});

const SearchConfField = ({ type, isSearchedSource, isProductPage, showSearchConf, keywords, openThickbox, deleteSearchResult }) => {
	const label = labels[type];

	if (type === 'mercari') {
		return <span className='__mainLabel'>商品詳細検索ができないサービスです</span>;
	}

	return (
		<>
			{isSearchedSource && <span className='__mainLabel'>検索元</span>}
			{!isSearchedSource && (
				<>
					{showSearchConf && (
						<>
							<Button
								icon={<Icon icon={search} />}
								isSecondary={true}
								onClick={() => {
									openThickbox(type, keywords);
								}}
							>
								{isProductPage ? `${label}で再検索` : `${label}でも検索`}
							</Button>
							<Button
								className={!isProductPage ? '-hide' : ''}
								icon={<Icon icon={closeSmall} />}
								isSecondary={true}
								onClick={() => {
									deleteSearchResult();
								}}
							>
								詳細リンクを削除
							</Button>
						</>
					)}
					{!showSearchConf && <span className='__mainLabel'>「すべて検索結果ページを表示」するため検索できません</span>}
				</>
			)}
		</>
	);
};

/**
 * LinkTableRow
 */
const LinkTableRow = ({ current, searchedAt, link, isProductPage, showSearchConf, keywords, openThickBox, deleteFunc }) => {
	const isSearchedSource = current === searchedAt;

	return (
		<tr>
			<th>{labels[current]}</th>
			<td>
				<UrlConfBtn url={link} isProductPage={isProductPage} />
			</td>
			<td>
				<SearchConfField
					type={current}
					isSearchedSource={isSearchedSource}
					isProductPage={isProductPage}
					showSearchConf={showSearchConf}
					keywords={keywords}
					openThickbox={(type, keyword) => openThickBox(type, keyword)}
					deleteSearchResult={() => {
						deleteFunc();
					}}
				/>
			</td>
		</tr>
	);
};

/**
 * BtnSettingTable
 */
export default ({ attrs, openThickbox, deleteAmazon, deleteRakuten, deleteYahoo }) => {
	// meta情報
	const keywords = attrs.keywords || '';
	const amazonAsin = attrs.asin || '';

	// 商品が検索された状態かどうか
	const searchedAt = attrs.searched_at;

	// 商品の検索結果を全て検索結果ページにするか
	const isAllSearchResult = !!attrs.is_all_search_result;

	// 各APIから検索済みかどうか
	const amazonDetailUrl = amazonAsin ? `https://www.amazon.co.jp/dp/${amazonAsin}` : '';
	const rakutenDetailUrl = attrs.rakuten_detail_url || '';
	const yahooDetailUrl = attrs.yahoo_detail_url || '';

	// 個別ページに遷移するかどうか
	const isAmazonToProductPage = !isAllSearchResult && amazonDetailUrl !== '';
	const isRakutenToProductPage = !isAllSearchResult && rakutenDetailUrl !== '';
	const isYahooToProductPage = !isAllSearchResult && yahooDetailUrl !== '';

	// 最終的に遷移するリンク先
	const amazonLink = isAmazonToProductPage ? amazonDetailUrl : genSerchedResultLink('amazon', keywords);
	const rakutenLink = isRakutenToProductPage ? rakutenDetailUrl : genSerchedResultLink('rakuten', keywords);
	const yahooLink = isYahooToProductPage ? yahooDetailUrl : genSerchedResultLink('yahoo', keywords);
	const mercariLink = genSerchedResultLink('mercari', keywords);

	const hasAffi = window.pchppVars.hasAffi;

	// memo: 「Whitespace text nodes cannot appear as a child of <tbody>.」警告を出さないように、非表示の列はnullを返す
	return (
		<div className='pchpp-btnTable'>
			<table className='__table'>
				<tbody>
					{hasAffi.amazon ? (
						<LinkTableRow
							current='amazon'
							searchedAt={searchedAt}
							link={amazonLink}
							isProductPage={isAmazonToProductPage}
							showSearchConf={!isAllSearchResult}
							keywords={keywords}
							openThickBox={openThickbox}
							deleteFunc={deleteAmazon}
						/>
					) : null}
					{hasAffi.rakuten ? (
						<LinkTableRow
							current='rakuten'
							searchedAt={searchedAt}
							link={rakutenLink}
							isProductPage={isRakutenToProductPage}
							showSearchConf={!isAllSearchResult}
							keywords={keywords}
							openThickBox={openThickbox}
							deleteFunc={deleteRakuten}
						/>
					) : null}
					{hasAffi.yahoo ? (
						<LinkTableRow
							current='yahoo'
							searchedAt={searchedAt}
							link={yahooLink}
							isProductPage={isYahooToProductPage}
							showSearchConf={!isAllSearchResult}
							keywords={keywords}
							openThickBox={openThickbox}
							deleteFunc={deleteYahoo}
						/>
					) : null}
					{hasAffi.mercari ? (
						<LinkTableRow
							current='mercari'
							searchedAt={searchedAt}
							link={mercariLink}
							isProductPage={false}
							showSearchConf={!isAllSearchResult}
							keywords={keywords}
							openThickBox={openThickbox}
							deleteFunc={() => {}}
						/>
					) : null}
				</tbody>
			</table>
		</div>
	);
};
