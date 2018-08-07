jQuery(document).ready(function() {
	// Update notification function
	$urlpre = document.location.protocol;
	$.ajax({
		url: $urlpre+'//comboot.io/version.php',
		error: function (thrownError) {
			pushNotify('Could not connect to update server!', 'danger', '5');
		},
		success: function (data) {
			$installed_version = $("meta[name=comboot-version]").attr("content");
			$theme_channel = $("meta[name=comboot-channel]").attr("content");
			$stable_newer_beta = data.stableNewerBeta;
			if ($theme_channel === 'stable' || $stable_newer_beta === '1') {
				$version = data.latestVersion;
				$release_date = data.releaseDate;
				$changelog = data.changelog;
				$dl_url = data.downloadURL;
				$title = 'New update available';
			} else if ($theme_channel === 'beta') {
				$version = data.latestBetaVersion;
				$release_date = data.betaReleaseDate;
				$changelog = data.betaChangelog;
				$dl_url = data.betaDownloadURL;
				$title = 'New beta update available';
			}

			if (Cookies.get('comboot-update-hide') != '1') {
				bootbox.dialog({
					title: $title,
					message: '<div class="alert alert-info"><ul class="list-unstyled"><li>New Version: <span class="version">' + $version + '</span> (' + $release_date + ')</li> ' +
						'<li>Your version: <span class="current-version">' + $installed_version + '</span></li></ul></div>' +
						'<br/>Changelog: <pre>' + $changelog + '</pre>' +
						'<br/><div class="text-center"><a href="' + $dl_url + '" class="btn btn-primary" target="_blank">Download</a></div>',
					buttons: {
						hide: {
							label: "Hide message for 14 days",
							className: "btn-warning",
							callback: function () {
								Cookies.set('comboot-update-hide', '1', { expires: 14, path: '/'});
								pushNotify('Update annocument will be hidden for 14 days on this device!', 'success', '8');
							}
						},
						success: {
							label: "I installed the update",
							className: "btn-success",
							callback: function () {
								Cookies.set('comboot-update-hide', '1', { expires: 14, path: '/'});
								pushNotify('Thank you for installing the update! If you updated, but this message does still appear, please change the meta value of theme-version in the overall-header.html file!', 'success', '15');
							}
						}
					}
				});
			}
		},
		timeout: 3000,
	});

	$.ajax({
		url: $urlpre+'//comboot.io/announcement.php',
		success: function (data) {
			$announcement_title = data.announcementTitle;
			$announcement_id = data.announcementID;
			$announcement = data.announcement;
			$announcement_link = data.announcementLink;
			$announcement_cookie = Cookies.set('announcement');

			if ($announcement_id !== '') {
				if ($announcement_id !== $announcement_cookie) {
					Cookies.remove('announcement-hide', {path: '/'} );
					Cookies.remove('announcement', {path: '/'} );
					Cookies.set('announcement', $announcement_id, {expires: 365, path: '/' });
				}
				if (Cookies.get('announcement-hide') != '1') {
					bootbox.dialog({
						title: $announcement_title,
						message: $announcement+'<br/><br/><div class="text-center alert alert-info">This message is only visible to admins and mods</div>',
						buttons: {
							read: {
								label: "Read More",
								className: "btn-primary",
								callback: function () {
									window.open($announcement_link);
								}
							},
							hide: {
								label: "Hide message",
								className: "btn-warning",
								callback: function () {
									pushNotify('This alert will be hidden until a further announcement is made.', 'success', 10);
									Cookies.set('announcement-hide', '1', { expires: 365, path: '/'});
								}
							}
						}
					});
				}
			} else {
				Cookies.remove('announcement', {path: '/'});
			}
		},
		timeout: 3000,
	});
});

function pushNotify(message, type, time) {
	$("body #wrap").before("<div class='push-notify alert alert-"+type+" text-center'></div>");
	$(".push-notify").text(message);
	$('.push-notify').show();
	$height = $('.push-notify').outerHeight();
	$('.push-notify').hide();
	setTimeout(function(){
		$(".push-notify").slideDown( 400 );
		$('#header-nav, #wrap').animate({
			'marginTop' : $height
		});
	}, 1000);
	timeHide = time * 1000;
	setTimeout(function(){
		$('.push-notify').slideUp( 400 );
		$('#header-nav, #wrap').animate({
			'marginTop' : 0
		});
	}, timeHide);
}
