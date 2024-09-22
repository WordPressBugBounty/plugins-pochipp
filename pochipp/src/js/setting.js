/**
 * 設定値による動的な変化
 */
(function ($) {
	$(function () {
		const menuBtn = $('.pchpp-setting__menubtn');
		menuBtn.click(function () {
			menuBtn.toggleClass('is-open');
		});

		(function () {
			const $boxPreview = $('.pchpp-setting__preview');
			const $box = $boxPreview.find('.pochipp-box');
			const $btns = $boxPreview.find('.pochipp-box__btns');
			const $price = $boxPreview.find('.pochipp-box__price');

			// レイアウト PC
			$('[name="pochipp_settings[box_layout_pc]"]').change(function () {
				const thisVal = $(this).val();
				$box.attr('data-lyt-pc', thisVal);
			});

			// レイアウト モバイル
			$('[name="pochipp_settings[box_layout_mb]"]').change(function () {
				const thisVal = $(this).val();
				$box.attr('data-lyt-mb', thisVal);
			});

			// img_position
			$('[name="pochipp_settings[img_position]"]').change(function () {
				const thisVal = $(this).val();
				$box.attr('data-img', thisVal);
			});

			// btn_style
			$('[name="pochipp_settings[btn_style]"]').change(function () {
				const thisVal = $(this).val();
				$box.attr('data-btn-style', thisVal);
			});

			// btn_radius
			$('[name="pochipp_settings[btn_radius]"]').change(function () {
				const thisVal = $(this).val();
				$box.attr('data-btn-radius', thisVal);
			});

			// max_column_pc
			$('[name="pochipp_settings[max_column_pc]"]').change(function () {
				const thisVal = $(this).val();
				$btns.attr('data-maxclmn-pc', thisVal);
			});

			// max_column_mb
			$('[name="pochipp_settings[max_column_mb]"]').change(function () {
				const thisVal = $(this).val();
				$btns.attr('data-maxclmn-mb', thisVal);
			});

			// display_price
			$('[name="pochipp_settings[display_price]"]').change(function () {
				const thisVal = $(this).val();
				$price.attr('data-disp-price', thisVal);
			});
		})();

		(function () {
			const $btns = $('.pchpp-inline-setting__preview .pochipp-inline__btnwrap');

			// inline_btn_style
			$('[name="pochipp_settings[inline_btn_style]"]').change(function () {
				const thisVal = $(this).val();
				$btns.attr('data-inline-btn-style', thisVal);
			});

			// inline_btn_radius
			$('[name="pochipp_settings[inline_btn_radius]"]').change(function () {
				const thisVal = $(this).val();
				$btns.attr('data-inline-btn-radius', thisVal);
			});

			// inline_btn_width
			$('[name="pochipp_settings[inline_btn_width]"]').change(function () {
				const thisVal = $(this).val();
				$btns.attr('data-inline-btn-width', thisVal);
			});
		})();
	});
})(jQuery);

/**
 * メルカリのリンク非表示設定モーダル
 */
(() => {
	// 選択件数表示
	let selectedItems = 0;
	updateSelectedItemsCount();

	// モーダル操作
	const body = document.querySelector('body');
	const modalOpenButton = document.querySelector('[data-mercari-modal-open]');
	const modalCloseElements = document.querySelectorAll('[data-mercari-modal-close]');
	modalOpenButton.addEventListener('click', () => {
		const modal = document.getElementById('mercari-modal');
		body.classList.add('position-fixed');
		modal.classList.remove('-hidden');
	});
	modalCloseElements.forEach((element) => {
		element.addEventListener('click', () => {
			const modal = document.getElementById('mercari-modal');
			body.classList.remove('position-fixed');
			modal.classList.add('-hidden');
			updateSelectedItemsCount();
		});
	});

	// 検索
	const input = document.querySelector('[data-search-items]');
	const noitemElement = document.querySelector('[data-noitem]');
	const itemtitles = document.querySelectorAll('.pchpp-hide-modal__linkWrapper label');
	input.addEventListener('input', (e) => {
		const keyword = e.target.value;
		itemtitles.forEach((title) => {
			const label = title.textContent;
			if (label.indexOf(keyword) !== -1) {
				selectedItems++;
				title.closest('.pchpp-setting__dl').style.display = 'block';
			} else {
				title.closest('.pchpp-setting__dl').style.display = 'none';
			}
		});
		if (selectedItems > 0) {
			noitemElement.style.display = 'none';
		} else {
			noitemElement.style.display = 'block';
		}
	});

	function updateSelectedItemsCount() {
		const selectedItemsElement = document.querySelector('[data-selected-items]');
		selectedItemsElement.textContent = document.querySelectorAll('[id^="pochipp_settings"]:checked').length;
	}
})();
