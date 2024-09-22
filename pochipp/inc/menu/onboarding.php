<div id="onboarding" class="pchpp-onboarding">
	<div class="pchpp-onboarding__background"></div>
	<div class="pchpp-onboarding__body">
		<div class="pchpp-onboarding__content" data-step="1">
			<h4 class="pchpp-onboarding__title">
				<span>Pochippは商品リンクを1クリックで作れる</span><br />
				<span>ブロックエディタ専用のプラグインです</span>
			</h4>
			<video height="240" width="480" src="<?php echo esc_url( POCHIPP_URL ); ?>assets/movie/onboarding_1.mp4" autoplay loop muted playsinline>
				<p>動画を再生するにはvideoタグをサポートしたブラウザが必要です。</p>
			</video>
			<div class="pchpp-onboarding__description">
				<span>※ クラッシックエディタはご利用いただけません</span>
			</div>
			<div class="pchpp-onboarding__button-wrapper">
				<button type="button" class="pchpp-onboarding__button">次へ</button>
			</div>
		</div>
		<div class="pchpp-onboarding__content -hidden" data-step="2">
			<h4 class="pchpp-onboarding__title">
				<span>解説記事の手順に沿って</span><br />
				<span>Pochippの初期設定を始めましょう！</span>
			</h4>
			<div class="pchpp-onboarding__blogcard-wrapper">
				<a href="https://pochipp.com/5428/" target="_blank">
					<div class="pchpp-onboarding__blogcard">
						<img height="90" width="120" src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/blogcard_1.png" alt="">
						<div class="pchpp-onboarding__blogcard_text">
							<p>Pochippインストール後の初期設定手順を解説</p>
						</div>
					</div>
				</a>
				<a href="https://pochipp.com/200/" target="_blank">
					<div class="pchpp-onboarding__blogcard">
						<img height="90" width="120"  src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/blogcard_2.png" alt="">
						<div class="pchpp-onboarding__blogcard_text">
							<p>Amazonのアフィリエイト・商品検索設定方法を解説</p>
						</div>
					</div>
				</a>
			</div>
			<div class="pchpp-onboarding__description">
				<span>
					※ 解説記事は上記ブログカードから確認できます<br />
					※ AmazonのAPI審査に合格しなくても利用できます
				</span>
			</div>
			<div class="pchpp-onboarding__button-wrapper">
				<button type="button" class="pchpp-onboarding__button">次へ</button>
			</div>
		</div>
		<div class="pchpp-onboarding__content -hidden" data-step="3">
			<h4 class="pchpp-onboarding__title">
				<span>セール情報設定機能を使って</span><br />
				<span>売上げアップを狙っていきましょう！</span>
			</h4>
			<img height="240" width="480" src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/onboarding_3.png" alt="セール紹介">
			<div class="pchpp-onboarding__description">
				<span>
					※ 楽天の「5と0のつく日」、Yahoo!の「5のつく日」<br />セールは自動で表示されます。詳細は<a href="https://pochipp.com/302/" target="_blank">こちら</a>
			</span>
			</div>
			<div class="pchpp-onboarding__button-wrapper">
				<button type="button" class="pchpp-onboarding__button">次へ</button>
			</div>
		</div>
		<div class="pchpp-onboarding__content -hidden" data-step="4">
			<h4 class="pchpp-onboarding__title">
				<span>Pochipp Pro（550円/月）を活用して</span><br />
				<span>さらに売上げアップを狙いましょう！</span>
			</h4>
			<img height="240" width="480"  src="<?php echo esc_url( POCHIPP_URL ); ?>assets/img/onboarding_4.png" alt="Pochipp Pro紹介">
			<div class="pchpp-onboarding__description">
				<span class="pchpp-onboarding__description">
					※ セール自動設定、クリック計測機能などがあります<br />
					Pochipp Proの詳細は<a href="https://pochipp.com/pochipp-pro/" target="_blank">こちら（別タブ）</a>
				</span>
			</div>
			<div class="pchpp-onboarding__button-wrapper">
				<button type="button" class="pchpp-onboarding__button">OK</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const contentSelector = '.pchpp-onboarding__content';
	const buttons = document.querySelectorAll('button.pchpp-onboarding__button');
	Array.from(buttons).forEach(element => element.addEventListener('click', () => {
		const displayContent = element.closest(contentSelector);

		const nextStep = parseInt(displayContent.getAttribute('data-step'), 10) + 1;
		  const nextDisplayContent = document.querySelector(`${contentSelector}[data-step="${nextStep}"]`);
		  if (nextDisplayContent === null) {
			  document.querySelector('#onboarding').remove();
			  return;
		}

		  displayContent.style.display = 'none';
		  nextDisplayContent.style.display = 'flex';
	}));
</script>
