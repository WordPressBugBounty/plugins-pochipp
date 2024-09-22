export const searchRegisteredItems = async ({ keywords, count }) => {
	const action = 'pochipp_search_registerd';
	const { ajaxUrl, ajaxNonce } = window.pchppVars;
	const params = {
		action,
		nonce: ajaxNonce,
		count,
	};
	if (keywords) {
		params.keywords = keywords;
	}
	const query = new URLSearchParams(params);

	const response = await fetch(`${ajaxUrl}?${query}`, {
		method: 'GET',
		cache: 'no-cache',
	}).then((res) => {
		if (res.ok) {
			return res.json();
		}
		throw new TypeError('Failed ajax!');
	});

	return response.registerd_items.map((item) => ({
		pid: item.post_id,
		title: item.title,
		image: item.custom_image_url || item.image_url,
		customBtnText: item.custom_btn_text || '',
		customBtnUrl: item.custom_btn_url || '',
		customBtnText2: item.custom_btn_text_2 || '',
		customBtnUrl2: item.custom_btn_url_2 || '',
	}));
};
