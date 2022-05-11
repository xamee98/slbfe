import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart';
import 'package:slbfe/views/home.dart';
import 'package:slbfe/views/register.dart';
import 'package:slbfe/views/user_logged_in.dart';

class UserLoginScreen extends StatelessWidget {
  const UserLoginScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AppScreen();
  }
}

class AppScreen extends StatefulWidget {
  const AppScreen({Key? key}) : super(key: key);

  @override
  State<AppScreen> createState() => _AppScreenState();
}

var deviceToken = "0";

class _AppScreenState extends State<AppScreen> {
  @override
  Widget build(BuildContext context) {
    TextEditingController nidController = TextEditingController();
    TextEditingController pwdController = TextEditingController();

    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: const Text('User Login'),
        ),
        body: Padding(
          padding: const EdgeInsets.all(8.0),
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
                        'Sign In',
                        style: TextStyle(
                          fontSize: 16,
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                ),
                // Login button press
                onPressed: () async {
                  print('Sign In');

                  String url = "https://192.168.1.150/API/?action=user_login";
                  String nid = nidController.text;
                  String pwd = pwdController.text;

                  var response = await post(Uri.parse(url), body: {
                    "national_id": nid,
                    "password": pwd,
                  });
                  print(jsonDecode(response.body));

                  if (response.statusCode == 200) {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                          builder: (context) => const MainScreen()),
                    );
                    setState(() {
                      var output = jsonDecode(response.body);
                      deviceToken = output["device_token"];
                    });
                  } else {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text("Invalid Credentials"),
                      ),
                    );
                  }
                  print(deviceToken);
                },
              ),
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
                onPressed: () {
                  print('Register');
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => const RegisterScreen()),
                  );
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}
