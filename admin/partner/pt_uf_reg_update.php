<?php
include_once("./_common.php");
$ptUfRegUpdate = new PtUfRegUpdate();

check_demo();

check_admin_token();

$ptUfRegUpdate->process();

//--------------------- 클래스 -------------------------------

class PtUfRegUpdate {

    public $mb_grade = 5;

    public function process() {
        $this->insert_shop_member();
        $this->insert_shop_partner();
        $this->insert_shop_logo();

        alert("가맹점 등록을 완료하였습니다.", TB_ADMIN_URL."/partner.php?code=plist");
    }

    // 탈퇴처리
    // 이 기능 보단, 아래의 솔루션 기본 기능을 이용할 것
    //  * 가맹점관리 - 가맹점 전체목록 - 삭제할 업체 선택 - 회원정보수정 - 탈퇴 선택
    // 주의! 특별한 상황 아니면 사용하지 말것.
    // 인서트한 디비 데이터 삭제
    public function delete_data($mb_id) {
        echo $mb_id."삭제";
        member_delete($mb_id);
    }

    // admin/member/member_register_form_update.php
    public function insert_shop_member() {

        if(!$_POST['id']) {
            alert('회원아이디가 없습니다. 올바른 방법으로 이용해 주십시오.');
        }

        $sql = " select count(*) as cnt from shop_member where id = '{$_POST['id']}' ";
        $row = sql_fetch($sql);
        if($row['cnt'])
            alert("이미 사용중인 회원아이디 입니다.");

        unset($value);
        $value['id']			= $_POST['id']; //회원아이디
        $value['name']			= $_POST['name']; //회원명
        $value['passwd']		= $_POST['passwd']; //비밀번호
        $value['birth_year']	= '1990'; //년
        $value['birth_month']	= '01'; //월
        $value['birth_day']		= '01'; //일
        $value['age']			= substr(date("Y")-$value['birth_year'],0,1).'0'; //연령대
        $value['birth_type']	= 'S'; //음력,양력
        $value['email']			= $_POST['email']; //이메일
        $value['cellphone']		= replace_tel($_POST['cellphone']); //핸드폰
        $value['telephone']		= replace_tel($_POST['telephone']); //전화번호
        $value['zip']			= $_POST['zip']; //우편번호
        $value['addr1']			= $_POST['addr1']; //주소
        $value['addr2']			= $_POST['addr2']; //상세주소
        $value['addr3']			= $_POST['addr3']; //참고항목
        $value['addr_jibeon']	= $_POST['addr_jibeon']; //지번주소
        $value['mailser']		= $_POST['mailser'] ? $_POST['mailser'] : 'N'; //E-Mail을 수신
        $value['smsser']		= $_POST['smsser'] ? $_POST['smsser'] : 'N'; //SMS를 수신
        $value['pt_id']			= $_POST['pt_id']; //추천인
        $value['reg_time']		= TB_TIME_YMDHIS; //가입일
        $value['grade']			= $this->mb_grade; //레벨 : [5] 페이투페이_가맹점

        $value['use_pg']			= 1; //개별 PG결제 허용
        $value['use_good']			= 1; //개별 상품판매 허용


        insert("shop_member", $value);

    }

    // admin/pop_memberformupdate.php
    public function insert_shop_partner() {

        // 카테고리 생성
        sql_member_category($_POST['id'], true);

        $pb = get_partner_basic($this->mb_grade);

        unset($pfrm);
        $pfrm['mb_id']			 = $_POST['id']; //회원명

        $value['shop_name']			= $_POST['name']; //쇼핑몰명
        $value['shop_name_us']		= $_POST['shop_name_us']; //쇼핑몰 영문명
        $value['saupja_yes']		= $_POST['saupja_yes']; //쇼핑몰 사업자노출 여부
        $value['company_type']		= $_POST['company_type']; //사업자유형
        $value['company_name']		= $_POST['name']; //회사명
        $value['company_saupja_no'] = $_POST['company_saupja_no']; //사업자등록번호
        $value['tongsin_no']		= $_POST['tongsin_no']; //통신판매신고번호
        $value['company_tel']		= replace_tel($_POST['telephone']); //대표전화
        $value['company_fax']		= $_POST['company_fax']; //대표팩스
        $value['company_item']		= $_POST['company_item']; //업태
        $value['company_service']	= $_POST['company_service']; //종목
        $value['company_owner']		= $_POST['company_owner']; //대표자명
        $value['company_zip']		= $_POST['zip']; //사업장우편번호

        $r = array( $_POST['addr1'], $_POST['addr2'], $_POST['addr3'] );
        $r = array_filter(array_map('trim',$r));
        $value['company_addr']		= implode(' ', $r); //사업장주소
        $value['company_hours']		= $_POST['company_hours']; //CS 상담가능시간
        $value['company_lunch']		= $_POST['company_lunch']; //CS 점심시간
        $value['company_close']		= $_POST['company_close']; //CS 휴무일
        $value['info_name']			= $_POST['company_owner']; //정보책임자 이름
        $value['info_email']		= $_POST['email']; //정보책임자 e-mail

        $value['head_title']		= $_POST['name']; //웹브라우져 타이틀

        $value['shop_provision'] = $this->get_shop_policy('shop_provision'); // 회원가입약관
        //$value['shop_private']	 = $_POST['shop_private']; // 개인정보 수집 및 이용
        $value['shop_policy']	 = $this->get_shop_policy('shop_policy'); // 개인정보처리방침

        $value['de_bank_use']			= 1; // 무통장입금
        $value['de_card_use']			= 1; // 신용카드
        $value['de_card_test']			= 1; // 테스트 결제

        $value['de_pg_service']			= $_POST['de_pg_service']; // 결제대행사

        $value['de_allat_mid']			= $_POST['de_allat_mid']; // KG올앳 상점아이디
        $value['de_allat_crosskey']		= $_POST['de_allat_crosskey']; // KG올앳 크로스키

        if( $value['de_pg_service'] == 'kiwoom') { // 키움페이
            $value['de_kiwoom_mid']			= 'CTS15232'; // 상점아이디
            $value['de_kiwoom_crosskey']		= 'Ufound01'; // 크로스키
        }

        $pfrm['anew_grade']		 = $this->mb_grade; //레벨 인덱스번호
        $pfrm['receipt_price']	 = $pb['gb_anew_price']; //분양개설비
        $pfrm['deposit_name']	 = $_POST['name']; //입금자명
        $pfrm['pay_settle_case'] = 1; //결제방식 1은 무통장, 2는 신용카드결제
        $pfrm['memo']			 = '관리자에 의해 승인처리 되었습니다.'; //메모
        $pfrm['state']			 = 1; //처리결과 1은 완료, 0은 대기
        $pfrm['reg_ip']			 = $_SERVER['REMOTE_ADDR'];
        $pfrm['reg_time']		 = TB_TIME_YMDHIS;
        $pfrm['update_time']	 = TB_TIME_YMDHIS;

        $pfrm = array_merge($pfrm, $value);

        insert("shop_partner", $pfrm);

        insert_anew_pay($_POST['id']); // 추천수수료

    }

    public function insert_shop_logo() {

        $value = array();

        $value['mb_id'] = $_POST['id'];

        $value['basic_logo_type'] = 't';
        $value['basic_logo_text'] = $_POST['name'];

        $value['mobile_logo_type'] = 't';
        $value['mobile_logo_text'] = $_POST['name'];


        insert("shop_logo", $value);
    }

    public function get_shop_policy($name) {
        $t_path = dirname(__FILE__).'/pt_uf_reg/doc';
        $html = implode('', file(($t_path."/{$name}.txt")));

        $html= str_replace("[[[company_name]]]", $_POST['name'], $html); // 회사명

        // 회사명 뒤 조사처리
        $josa_r = array("은(는)","이(가)");
        foreach($josa_r as $v) {
            $t = $this->josa($_POST['name'].$v);
            $josa = mb_substr($t, -1,1, 'UTF-8');
            $html= str_replace("[[[{$v}]]]", $josa, $html);
        }

        $html= str_replace("[[[company_tel]]]", replace_tel($_POST['telephone']), $html); // 대표전화번호
        $html= str_replace("[[[info_email]]]", $_POST['email'], $html); // 정보책임자 e-mail
        $html= str_replace("[[[info_name]]]", $_POST['company_owner'], $html); // 정보책임자 이름


        return addslashes($html);
    }

    // 한글 조사처리
    // https://zetawiki.com/wiki/PHP_%ED%95%9C%EA%B5%AD%EC%96%B4_%EC%A1%B0%EC%82%AC_%EC%B2%98%EB%A6%AC
    public function josa($ex) {
        $pps = array("은(는)","이(가)","을(를)","과(와)");
        foreach( $pps as $pp ) {
            $ex = preg_replace_callback("/(.)".preg_quote($pp)."/u",
                function($matches) use($pp) {
                    $ch = $matches[1];
                    $has_jongsung = true;
                    if(preg_match('/[가-힣]/',$ch)) {
                        $code = (ord($ch{0})&0x0F)<<12 | (ord($ch{1})&0x3F)<<6 | (ord($ch{2})&0x3F);
                        $has_jongsung = ( ($code-16)%28 != 0 );
                    }
                    else if(preg_match('/[2459]/', $ch)) $has_jongsung = false;

                    return $ch.mb_substr($pp,($has_jongsung?0:2),1, 'UTF-8');
                }, $ex);
        }
        return $ex;
    }}

?>