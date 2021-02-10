function getorderStatus(elem,add = 0){
  res = {
    success: "1",
    data: [
      {
        id: "1",
        status:
          "\u062a\u0645 \u062a\u0633\u062c\u064a\u0644 \u0627\u0644\u0637\u0644\u0628",
        note: "/",
      },
      {
        id: "2",
        status:
          "\u062c\u0627\u0647\u0632 \u0644\u0644\u0627\u0631\u0633\u0627\u0644",
        note: "",
      },
      {
        id: "3",
        status:
          "\u0628\u0627\u0644\u0637\u0631\u064a\u0642 \u0645\u0639 \u0627\u0644\u0645\u0646\u062f\u0648\u0628",
        note: "",
      },
      {
        id: "4",
        status:
          "\u062a\u0645 \u062a\u0633\u0644\u064a\u0645 \u0627\u0644\u0637\u0644\u0628",
        note: "",
      },
      {
        id: "5",
        status:
          "\u0627\u0633\u062a\u0628\u062f\u0627\u0644 \u0627\u0644\u0637\u0644\u0628",
        note: "",
      },
      {
        id: "6",
        status: "\u0631\u0627\u062c\u0639 \u062c\u0632\u0626\u064a",
        note: "",
      },
      { id: "7", status: "\u0645\u0624\u062c\u0644 ", note: "" },
      {
        id: "8",
        status: "\u062a\u063a\u064a\u0631 \u0639\u0646\u0648\u0627\u0646",
        note: "",
      },
      {
        id: "9",
        status: "\u0631\u0627\u062c\u0639 \u0643\u0644\u064a",
        note:
          "\u0644\u0646 \u062a\u0643\u0648\u0646 \u0647\u0646\u0627\u0643 \u0627\u062c\u0631\u0629 \u062a\u0648\u0635\u064a\u0644",
      },
      {
        id: "13",
        status: "\u0627\u0639\u0627\u062f\u0629 \u0627\u0631\u0633\u0627\u0644",
        note: "",
      },
    ],
  };
  if(add){
     elem.append(
       '<option style="" value="">-- اختر الحالة --</option>'
     );
   }
   $.each(res.data,function(){
     bg ="";
     if(this.id == 4){
       bg ="#9CDE7C";
     }else if(this.id == 5){
       bg ="#FFFFAC";
     }else if(this.id == 9){
       bg ="#F2A69B";
     }else if(this.id == 4){
       bg ="";
     }else if(this.id == 4){
       bg ="";
     }
     elem.append(
       '<option style="background-color:'+bg+'" value="'+this.id+'">'+this.status +'</option>'
     );
    });
    elem.selectpicker('refresh');

});
}
