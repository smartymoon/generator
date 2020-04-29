<?php
namespace Smartymoon\Generator;

class Faker 
{
    static public function random_image($shape) {
        if ($shape === 'square') {
            return "https://dummyimage.com/150x150";
        } else if ($shape == 'fat') {
            return "https://dummyimage.com/400x250";
        } else if ($shape == 'tall') {
            return "https://dummyimage.com/250x350";
        }
    }

    static public function random_chinese($min = 3, $max = 0) {
        $str = '繁忙工作生活中找寻自我的空间试问这些年是如何安排闲暇时间才有这般自信平凡如我也自有属于自己的一片天地读书一卷在手半床诗书思索四大名著之深刻品读安妮宝贝之哀伤感受郭敬明之锐利想象勃朗特之困顿汲取公文之精髓梳理法条之奥妙三十年来与书相伴不离不弃给书籍所作的每一行圈点勾画每一段心得摘抄都成就了我内心深处的气质和从容写作做足不出户而写尽天下事的笔者是我从小的梦想尽管未曾实现虽不能至心向往之这些年来就算再忙也好这一支笔这一双手却是从未停下过也曾把那些幼嫩的散文小诗传进空间里与好友分享也曾尝试过编演了些许情节的小说留给多年后的自己谈笑也曾苦心思考着公文写作上自己的差距到底在哪里究竟还有多少提升的空间也曾忙乱到无暇分身时仍不忘用一句话记录下这一整天的心情回顾经年累月积攒下的每一字一句伴随的精进成熟相信业精于勤坚持笔耕不辍执著自己的梦还好没有让自己后悔过弹琴还记得初中时写过我的爱是高低音的辉映我的梦是黑白键的深情钢琴似最亲密无间的老友陪伴我二十余载心情舒畅时我会自弹自唱西游记里的女儿情心有不悦时我会弹奏高亢激昂的命运万分感慨时我会重温学生时代获过一等奖的秋日私语读书有所得时也会上演红楼中的葬花吟枉凝眉空闲无忧时会陪伴女儿家人一起徜徉在音乐的世界有集体活动时也会编曲伴奏精心排练终不负毕生所学琴是我一生挚友见证我一步步成长相伴我所有的情思唱歌音乐的世界总离不开歌声我的歌喉谈不上美妙但我喜欢歌唱酷狗音乐里寻出喜爱的歌者我每每静静聆听用心感受意境记录美好的歌词然后将之化作自己的歌唱给女儿听唱给自己听也会坐在钢琴前高歌一曲自己为自己伴奏自己为自己喝彩或约上三五知己良朋里小坐唱生活唱心情唱快乐也唱悲伤早教不觉的我的一对双胞胎女儿已经快两岁了因为工作原因我无法像我的母亲照顾我时那样身为人母我对女儿有所亏欠可是每当看着她们天真无邪的目光和愈发可爱的面容我便暗自庆幸幸好有她们在幸好有她们支撑我幸好我没有错过她们每一个成长的过程陪女儿玩耍启蒙女儿的早期教育是我最最重视的事情也正是因为这样的时间不多才会格外珍惜这个阶段在我说话时她们也会跟着牙牙学语我读书时她们也会跟着翻动书页我写字时她们也会抢过笔来到处涂鸦我弹琴时她们也会抢着上来按动键盘我唱歌时她们会眼睛都不眨地盯着我倾听但愿待她们长大以后能有一个不倦努力的母亲的形象深深烙印在脑海里时刻影响并启迪她们走出自己的人生散步喜欢在周末的午后时光与爱人一起去兜风驰骋于青山碧水之间忘我于艳阳白云之下畅谈心事舒解烦难一周的辛劳烟消云散给自己一个新的好状态偶有长假两人一起去旅游从丽江古城到峨嵋金顶至北国雪乡携手遨游于这般绝色风景也喜欢斜倚在床上品着咖啡看连续剧跟随红楼里的十二金钗同喜同悲沉浸于甄嬛里的美人心计对着韩国的搞笑一家人捧腹大笑抱着女儿手舞足蹈地看动画片爱探险的朵拉然而由于工作性质的原因不可能每天都如此休闲难免经常加班莫说那些自己多年钟爱的兴趣爱好就连一直想备战的司法考试似也成了妄';
        $length = mb_strlen($str);
        if ($max > 0) {
            return mb_substr($str, rand(0, $length - $max), rand($min, $max));
        } 
        $start = rand(0, $length - $min);
        return mb_substr($str, rand(0, $length - $min), $min);
    }

    static public function random_date($period = 'future') {
        $now = now();
        if ($period == 'future') {
            return $now->addSeconds(rand(30*24*60*60, 30*24*60*60*12));
        } else if ($period == 'past') {
            return $now->subSeconds(rand(30*24*60*60, 30*24*60*60*12));
        }
    }

    static public function random_url() {
        $array = [
            'http://baidu.com',
            'http://zhihu.com',
            'http://taobao.com',
            'http://qq.com',
        ];
        return $array[array_rand($array)];
    }

    static public function enum() {
        $items = func_get_args();
        return $items[array_rand($items)];
    }

    static public function fixed($data) {
        return $data;
    }

    static public function rand($start, $end) {
        return rand($start, $end);
    }

    static public function random_phone()
    {
        $arr = [
            130,131,132,133,134,135,136,137,138,139,
            144,147,
            150,151,152,153,155,156,157,158,159,
            176,177,178,
            180,181,182,183,184,185,186,187,188,189,
        ];
        return $arr[array_rand($arr)].' '.mt_rand(1000,9999).' '.mt_rand(1000,9999);
    }

    static public function random_id_card()
    {
        $date = self::random_date('past');
        $no =  '360424'. $date->format('Ymd') . '0795';
    }

    static public function english_word($len = 6)
    {
        $word = range('a', 'z');
        shuffle($word);
        return substr(implode($word), 0, $len);
    }
}