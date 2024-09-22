import { applyFilters, hasFilter } from '@wordpress/hooks';
import { BaseControl } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import ProIntroduction, { textkeys } from './ProIntroduction';

const ExSettting = ({ parsedMeta, updateMetadata }) => {
	if (!hasFilter('pochipp.exSaleSetting', 'pochipp-pro')) {
		return (
			<>
				<span className='components-base-control components-base-control__label'>カスタムセール情報</span>
				<ProIntroduction textkey={textkeys.customSale} />
			</>
		);
	}
	return applyFilters('pochipp.exSaleSetting', null, { parsedMeta, updateMetadata });
};

export default ({ parsedMeta, updateMetadata, isShopLinkType }) => {
	const shops = [
		{
			name: 'amazon',
			label: 'Amazonセール情報',
			value: parsedMeta.amazon_sale_text || '',
			saleStart: parsedMeta.amazon_sale_start || '',
			saleEnd: parsedMeta.amazon_sale_end || '',
		},
		{
			name: 'rakuten',
			label: '楽天セール情報',
			value: parsedMeta.rakuten_sale_text || '',
			saleStart: parsedMeta.rakuten_sale_start || '',
			saleEnd: parsedMeta.rakuten_sale_end || '',
		},
		{
			name: 'yahoo',
			label: 'Yahooセール情報',
			value: parsedMeta.yahoo_sale_text || '',
			saleStart: parsedMeta.yahoo_sale_start || '',
			saleEnd: parsedMeta.yahoo_sale_end || '',
		},
		{
			name: 'mercari',
			label: 'メルカリセール情報',
			value: parsedMeta.mercari_sale_text || '',
			saleStart: parsedMeta.mercari_sale_start || '',
			saleEnd: parsedMeta.mercari_sale_end || '',
		},
	];

	return (
		<BaseControl>
			{isShopLinkType &&
				shops.map((shop) => (
					<Fragment key={shop.name}>
						<span className='components-base-control components-base-control__label'>{shop.label}</span>
						<div className='__textArea -fullWidth'>
							<span>表示</span>
							<input
								type='text'
								value={shop.value}
								onChange={(e) => {
									updateMetadata({ [`${shop.name}_sale_text`]: e.target.value });
								}}
							/>
						</div>
						<div className='__textArea'>
							<span>期間</span>
							<input
								type='datetime-local'
								value={shop.saleStart}
								onChange={(e) => {
									updateMetadata({ [`${shop.name}_sale_start`]: e.target.value });
								}}
							/>
							<span className='__nami'>~</span>
							<input
								type='datetime-local'
								value={shop.saleEnd}
								onChange={(e) => {
									updateMetadata({ [`${shop.name}_sale_end`]: e.target.value });
								}}
							/>
						</div>
					</Fragment>
				))}
			<ExSettting parsedMeta={parsedMeta} updateMetadata={updateMetadata} />
		</BaseControl>
	);
};
