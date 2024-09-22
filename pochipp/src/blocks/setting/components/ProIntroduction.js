export const textkeys = {
	customImg: 'customImg',
	customSale: 'customSale',
};

export default ({ textkey }) => {
	const introductions = {
		[textkeys.customImg]: 'メディアライブラリ内から商品画像を設定',
		[textkeys.customSale]: 'カスタムボタンのセール情報を設定',
	};

	return (
		<p style={{ fontSize: '13px' }}>
			<a href='https://pochipp.com/pochipp-pro/' target='_blank' rel='noreferrer'>
				Pochipp Pro
			</a>
			を導入すると、{introductions[textkey]}できるようになります。
		</p>
	);
};
