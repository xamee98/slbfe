// ignore_for_file: unnecessary_null_comparison

import 'dart:convert';

import 'package:flutter/material.dart';

import 'package:http/http.dart' as http;
import 'package:http/http.dart';

class RegisterScreen extends StatelessWidget {
  const RegisterScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MyApp();
  }
}

class MyApp extends StatefulWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  State<MyApp> createState() => _MyAppState();
}

// Future submitRegisterModel(String nid, String age, String name, String adr,
//     String cl, String prof, String email, String af, String pwd) async {
//   var sendData = {

//     'age': age,
//     'name': name,
//     'address': adr,
//     'current_location': cl,
//     'profession': prof,
//     'email': email,
//     'affiliation': af,
//     'password': pwd
//   };
//   var res = await http.get(
//       Uri.parse('http://192.168.1.150/API/?action=test'),
//   );
//   // var request =
//   //     http.Request('POST', Uri.parse('http://10.0.2.2:8081/?action=citizen'),
//   //
//   //     );
//   print("hello");
//   print(res);
//   // request.bodyFields = {
//   //   'age': age,
//   //   'name': name,
//   //   'address': adr,
//   //   'current_location': cl,
//   //   'profession': prof,
//   //   'email': email,
//   //   'affiliation': af,
//   //   'password': pwd
//   // };

//   // http.StreamedResponse response = await request.send();
//   //
//   // if (response.statusCode == 200) {
//   //   final String responseString = response.toString();
//   //   return registerModelFromJson(responseString);
//   // } else {
//   //   print(response.reasonPhrase);
//   //   return registerModelFromJson(response.toString());
//   // }
// }

class _MyAppState extends State<MyApp> {
  @override
  Widget build(BuildContext context) {
    TextEditingController nidController = TextEditingController();
    TextEditingController ageController = TextEditingController();
    TextEditingController nameController = TextEditingController();
    TextEditingController adrController = TextEditingController();
    TextEditingController latController = TextEditingController();
    TextEditingController longController = TextEditingController();
    TextEditingController profController = TextEditingController();
    TextEditingController emailController = TextEditingController();
    TextEditingController afController = TextEditingController();
    TextEditingController pwdController = TextEditingController();

    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: const Text('Register User'),
        ),
        body: Padding(
          padding: const EdgeInsets.all(8.0),
          child: SingleChildScrollView(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              mainAxisSize: MainAxisSize.max,
              children: [
                TextField(
                  controller: nidController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'National ID No.',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: false,
                  controller: ageController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Age',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: false,
                  controller: nameController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Name',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: false,
                  controller: adrController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Address',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: false,
                  controller: latController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Latitude',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: false,
                  controller: longController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Longitude',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: false,
                  controller: profController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Profession',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: false,
                  controller: emailController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Email',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: false,
                  controller: afController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Affiliation',
                  ),
                ),
                Container(
                  height: 20,
                ),
                TextField(
                  obscureText: true,
                  controller: pwdController,
                  decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Password',
                  ),
                ),
                Container(
                  height: 20,
                ),
                // _register == null ? Container() :
                // Text(
                //   '${_register.age} and ${_register.name} and ${_register.email}'
                // ),
                TextButton(
                  child: Container(
                    height: 50.0,
                    width: double.infinity,
                    child: Card(
                      shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(15.0)),
                      color: Colors.blue,
                      child: const Center(
                        child: Text(
                          'Register User',
                          style: TextStyle(
                            fontSize: 16,
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  ),
                  onPressed: () async {
                    var nid = nidController.text;
                    var age = ageController.text;
                    var name = nameController.text;
                    var adr = adrController.text;
                    var lat = latController.text;
                    var long = longController.text;
                    var prof = profController.text;
                    var email = emailController.text;
                    var af = afController.text;
                    var pwd = pwdController.text;

                    // final RegisterModel register = await submitRegisterModel(nid,
                    //     age, name, adr, cl, prof, email, af, pwd);
                    String url =
                        "https://192.168.1.150/API/?action=user_register";
                    var response = await post(Uri.parse(url), body: {
                      "national_id": nid,
                      "name": name,
                      "age": age,
                      "address": adr,
                      "latitude": lat,
                      "longitude": long,
                      "profession": prof,
                      "email": email,
                      "affiliation": af,
                      "password": pwd
                    });

                    // print(
                    //     "${nid}\n${name}\n${age}\n${adr}\n${lat}\n${long}\n${prof}\n${email}\n${af}\n${pwd}");

                    print(jsonDecode(response.body));

                    // print(jsonDecode(res.body));

                    // setState(() {
                    //   _register = register;
                    // });
                  },
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
