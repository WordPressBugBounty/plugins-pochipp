import { Popover, TextControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { Icon, image as imageIcon, link as linkIcon, button as buttonIcon } from '@wordpress/icons';
import { searchRegisteredItems } from '../api/searchRegisteredItems';
import Item from './Item';

const types = {
	button: {
		icon: buttonIcon,
		title: 'Pochipp インラインボタン',
	},
	link: {
		icon: linkIcon,
		title: 'Pochipp インラインリンク',
	},
	img: {
		icon: imageIcon,
		title: 'Pochipp インライン画像',
	},
};

export default ({
	selectedType, // "button" | "link" | "img"
	value,
	anchorRef,
	onChange,
	closePopover,
}) => {
	const [items, setItems] = useState([]);
	const [keyword, setKeyword] = useState('');
	const { title, icon } = types[selectedType];

	// キーワード検索された時
	const fetchRegisteredItems = async (searchWord) => {
		// とりあえず5つを上限として返却する
		setItems(await searchRegisteredItems({ keywords: searchWord, count: 5 }));
	};

	// 初期状態で表示するリスト
	const fetchRegisteredItemsDefault = async () => {
		setItems(await searchRegisteredItems({ keywords: null, count: 5 }));
	};

	useEffect(() => {
		fetchRegisteredItemsDefault();
	}, []);

	return (
		<Popover
			anchorRef={anchorRef}
			className='pochipp-popover'
			onClose={() => {
				closePopover();
			}}
		>
			<div className='pochipp-popover__title'>
				<Icon icon={icon} />
				<span>{title}</span>
			</div>
			<div className='pochipp-popover__body'>
				<TextControl
					className='pochipp-popover__search'
					placeholder='登録済み商品をキーワードで検索...'
					value={keyword}
					onChange={(newText) => {
						setKeyword(newText);
						fetchRegisteredItems(keyword);
					}}
				/>
				<div className='pochipp-popover__list'>
					{items.map((item) => (
						<Item
							key={item.pid}
							selectedType={selectedType}
							value={value}
							item={item}
							onChange={onChange}
							closePopover={closePopover}
						/>
					))}
				</div>
			</div>
		</Popover>
	);
};
