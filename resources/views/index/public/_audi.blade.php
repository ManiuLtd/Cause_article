// 喜欢
$(".bls-xin").click(function () {
$(this).css('color','#f13c50')
})

// 音频播放
$('i.icon').click(function (){
if($(this).hasClass('bls-pause')){
$(this).removeClass('bls-pause').addClass('bls-play');
window.playbox.pause();
}else{
var obj = $(this);
obj.removeClass('bls-play').addClass('bls-pause')
if(window.playbox){
playbox.play();
}else{
window.playbox = document.createElement('audio');
playbox.src = $(this).attr('data-src');
playbox.play();
$('.audio .progress em').css('visibility','visible');
playbox.onprogress = function (){
//                    console.log(playbox.buffered.length);
if(playbox.buffered.length && !window.loaded && playbox.buffered){
playbox.buffered.start(0);
var precent = parseInt(playbox.buffered.end(0) / window.duration * 100);
$('.audio .progress span').css('width',precent + '%');
}
}
playbox.onloadeddata = function (){
window.loaded = true;
$('.audio .progress span').css('width','100%');
}
playbox.oncanplay = function (){
window.duration = playbox.duration;
var min = parseInt(playbox.duration / 60),sec = parseInt(playbox.duration) % 60;
var durationText = (min < 10 ? '0' + min : min) + ':' + (sec < 10 ? '0' + sec : sec);
$('.audio .duration .end').text(durationText);
playbox.play();
}
playbox.onended = function (){
window.played = null;
window.duration = null;
clearInterval(window.timer);
$('.audio .progress').children().removeAttr('style');
$('.audio .duration .start').text('00:00');
obj.removeClass('bls-pause').addClass('bls-play');
window.playbox = null;
}
playbox.onplay = function (){
window.timer = setInterval(function (){
window.played ? played += 1 : played = 1;
if(played > duration) played = duration;
$('.audio .progress em').css('width',parseInt(played / duration * 98) + '%');
var min = parseInt(played / 60),sec = parseInt(played) % 60;
var durationText = (min < 10 ? '0' + min : min) + ':' + (sec < 10 ? '0' + sec : sec);
$('.audio .duration .start').text(durationText);
},1000);
}
playbox.onpause = function (){
clearInterval(window.timer);
}
}
if(window.playbox){
playbox.onerror = function (){
clearInterval(window.timer);
obj.removeClass('bls-pause').addClass('bls-play');
$('.audio .progress em').css('visibility','hidden');
//$('#audio .media p').text('音频加载失败').css('color', '#f00');
playbox.pause();
window.loaded = false;
}
}
if(window.loaded == false){
obj.removeClass('bls-pause').addClass('bls-play');
}
}
});