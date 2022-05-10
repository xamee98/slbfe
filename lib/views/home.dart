import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:slbfe/views/officer_login.dart';
import 'package:slbfe/views/user_login.dart';

class HomePage extends StatelessWidget {
  const HomePage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: const Text('Welcome'),
        ),
        body: Padding(
          padding: const EdgeInsets.all(8.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            mainAxisSize: MainAxisSize.max,
            children: [
              TextButton(
                child: Container(
                  height: 100.0,
                  width: double.infinity,
                  child: Card(
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(15.0)),
                    color: Colors.blue,
                    child: const Center(
                      child: Text(
                        'User Login',
                        style: TextStyle(
                          fontSize: 25,
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                ),
                onPressed: () {
                  print('First Button');
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => const UserLoginScreen()),
                  );
                },
              ),
              // TextButton(
              //   child: Container(
              //     height: 100.0,
              //     width: double.infinity,
              //     child: Card(
              //       shape: RoundedRectangleBorder(
              //           borderRadius: BorderRadius.circular(15.0)),
              //       color: Colors.blue,
              //       child: const Center(
              //         child: Text(
              //           'Officer Login',
              //           style: TextStyle(
              //             fontSize: 25,
              //             color: Colors.white,
              //             fontWeight: FontWeight.bold,
              //           ),
              //         ),
              //       ),
              //     ),
              //   ),
              //   onPressed: () {
              //     print('Second Button');
              //     Navigator.push(
              //       context,
              //       MaterialPageRoute(
              //           builder: (context) => const OfficerLoginScreen()),
              //     );
              //   },
              // ),
            ],
          ),
        ),
      ),
    );
  }
}
