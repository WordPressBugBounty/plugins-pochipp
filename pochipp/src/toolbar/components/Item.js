import { Button } from '@wordpress/components';
import { insert } from '@wordpress/rich-text';

const shops = {
	amazon: 'Amazon',
	rakuten: '楽天',
	yahoo: 'Yahoo',
	mercari: 'メルカリ',
	custom1: 'カスタムボタン',
	custom2: 'カスタムボタン2',
};

const shortcodes = {
	button: 'pochipp_btn',
	link: 'pochipp_link',
	img: 'pochipp_img',
};

const showBtn = (shop, hasAffi, item) => {
	if (shop === 'custom1') {
		return item.customBtnText && item.customBtnUrl;
	}
	if (shop === 'custom2') {
		return item.customBtnText2 && item.customBtnUrl2;
	}
	return hasAffi && hasAffi[shop];
};

const label = (shop, item) => {
	if (shop === 'custom1') {
		return item.customBtnText;
	}
	if (shop === 'custom2') {
		return item.customBtnText2;
	}
	return shops[shop];
};

const generateCvkey = () => {
	const characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	const length = 8;
	return Array.from(Array(length))
		.map(() => characters[Math.floor(Math.random() * characters.length)])
		.join('');
};

const generateShortcode = (type, pid, title, cvKeyTag, shop) => {
	const shortcode = shortcodes[type];
	const shopLabel = shops[shop];
	switch (type) {
		case 'button':
			return `[${shortcode} id="${pid}" shop="${shop}"${cvKeyTag}]${shopLabel}[/${shortcode}]`;
		case 'link':
			return `[${shortcode} id="${pid}" shop="${shop}"${cvKeyTag}]${title}[/${shortcode}]`;
		case 'img':
			return `[${shortcode} id="${pid}" shop="${shop}"${cvKeyTag}]`;
		default:
			return '';
	}
};

export default ({
	selectedType, // "button" | "link" | "img"
	value,
	item,
	onChange,
	closePopover,
}) => {
	const { hasAffi } = window.pchppVars;
	const hasPro = window.pchppProVars !== undefined;
	const isAllHidden = hasAffi && Object.values(hasAffi).every((v) => v === '');
	const cvKeyTag = hasPro ? ` cvkey="${generateCvkey()}"` : '';

	return (
		<div className='pochipp-popover__card'>
			<div className='pochipp-popover__card-image'>
				<img src={item.image} width={100} height={100} alt='' />
			</div>
			<div className='pochipp-popover__card-body'>
				<p className='pochipp-popover__card-description'>{item.title}</p>
				<div className='pochipp-popover__card-btns'>
					{isAllHidden && (
						<div className='pochipp-popover__disabled'>
							<a href='https://pochipplocal.local/wp-admin/edit.php?post_type=pochipps&page=pochipp_settings&tab=basic'>
								ポチップ設定ページ
							</a>
							から、各ショップの「アフィリエイト設定」を行ってください。
						</div>
					)}
					{Object.keys(shops).map((shop) => {
						return (
							showBtn(shop, hasAffi, item) && (
								<Button
									key={`btn-${shop}`}
									text={label(shop, item)}
									isSecondary
									onClick={() => {
										closePopover();
										onChange(
											insert(
												value,
												generateShortcode(selectedType, item.pid, item.title, cvKeyTag, shop),
												value.start,
												value.end
											)
										);
									}}
								/>
							)
						);
					})}
				</div>
			</div>
		</div>
	);
};
