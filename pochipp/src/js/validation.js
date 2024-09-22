// eslint-disable-next-line
window.addEventListener('load', function () {
	const amazonSearchElem = document.querySelector('.pchpp-setting__div.amazon-search');
	const amazonAffiliateElem = document.querySelector('.pchpp-setting__div.amazon-affiliate');
	const rakutenAppidElem = document.querySelector('.pchpp-setting__div.rakuten-appid');
	const rakutenAffiliateElem = document.querySelector('.pchpp-setting__div.rakuten-affiliate');
	const yahooAppidElem = document.querySelector('.pchpp-setting__div.yahoo-appid');
	const yahooAffiliateElem = document.querySelector('.pchpp-setting__div.yahoo-affiliate');
	const moshimoElem = document.querySelector('.pchpp-setting__div.moshimo');
	const mercariElem = document.querySelector('.pchpp-setting__div.mercari');

	if (amazonSearchElem && amazonAffiliateElem) {
		validateAmazon();
	}
	if (rakutenAppidElem && rakutenAffiliateElem) {
		validateRakuten();
	}
	if (yahooAppidElem && yahooAffiliateElem) {
		validateYahoo();
	}
	if (!!moshimoElem) {
		validateMoshimo();
	}
	if (!!mercariElem) {
		validateMercari();
	}
});

const validateAmazon = () => {
	const selectorList = {
		accessKey: '.pchpp-setting__div.amazon-search dl:nth-child(1) input',
		secretKey: '.pchpp-setting__div.amazon-search dl:nth-child(2) input',
		affiliate: '.pchpp-setting__div.amazon-affiliate dd input',
	};

	Object.values(selectorList).forEach((selector) => {
		document.querySelector(selector).addEventListener('input', (event) => {
			const value = event.target.value;

			const errors = [includesSpace(value)];
			const message = errors.find((error) => error !== '') || '';

			// ここに表示する処理
			outputError(event.target.closest('dd'), message);
		});
	});
};

const validateRakuten = () => {
	const selectorList = {
		appId: '.pchpp-setting__div.rakuten-appid',
		affiliate: '.pchpp-setting__div.rakuten-affiliate',
	};

	Object.values(selectorList).forEach((selector) => {
		document.querySelector(selector).addEventListener('input', (event) => {
			const value = event.target.value;

			const errors = [includesSpace(value)];
			const message = errors.find((error) => error !== '') || '';

			// ここに表示する処理
			outputError(event.target.closest('dd'), message);
		});
	});
};

const validateYahoo = () => {
	// validate処理
	const selector = '.pchpp-setting__div.yahoo-appid';
	document.querySelector(selector).addEventListener('input', (event) => {
		const value = event.target.value;

		const errors = [includesSpace(value)];
		const message = errors.find((error) => error !== '') || '';

		// ここに表示する処理
		outputError(event.target.closest('dd'), message);
	});

	// trim処理
	document.querySelector('#yahoo_linkswitch').addEventListener('input', (event) => {
		event.target.value = event.target.value.replace(/[^0-9]/g, '');
	});
};

const validateMoshimo = () => {
	const selectorList = {
		amazon: '.pchpp-setting__dl.-amazon > dd input',
		rakuten: '.pchpp-setting__dl.-rakuten > dd input',
		yahoo: '.pchpp-setting__dl.-yahoo > dd input',
	};

	Object.values(selectorList).forEach((selector) => {
		document.querySelector(selector).addEventListener('input', (event) => {
			const strCountLimit = 7;
			const expectedType = 'number';
			const value = event.target.value;

			const errors = [overWordCount(value, strCountLimit), invalidType(value, expectedType)];
			const message = errors.find((error) => error !== '') || '';

			// ここに表示する処理
			outputError(event.target.closest('dd'), message);
		});
	});
};

const validateMercari = () => {
	const selector = '#mercari_ambassador_id';
	document.querySelector(selector).addEventListener('input', (event) => {
		const strCount = 10;
		const expectedType = 'number';
		const value = event.target.value;

		const errors = [invalidType(value, expectedType), belowWordCount(value, strCount), overWordCount(value, strCount)];
		const message = errors.find((error) => error !== '') || '';

		// ここに表示する処理
		outputError(event.target.closest('dd'), message);
	});
};

const outputError = (element, message) => {
	const color = message !== '' ? '#d63638' : '';
	element.querySelector('input').style.borderColor = color;
	element.querySelector('.errMessage').textContent = message;
};

const includesSpace = (target) => {
	return target.includes(' ') || target.includes('　') ? '不要なスペースが含まれています' : '';
};

const belowWordCount = (target, count) => {
	return target.length < count ? `入力可能な文字数は${count}文字以上です。` : '';
};

const overWordCount = (target, limit) => {
	return target.length > limit ? `入力可能な文字数は${limit}文字までです。` : '';
};

const invalidType = (target, type) => {
	if (type === 'number') {
		const matches = target.match(/^[0-9]*$/);
		return matches === null || target !== matches[0] ? '入力可能な文字は数値のみです。' : '';
	}

	return '';
};
