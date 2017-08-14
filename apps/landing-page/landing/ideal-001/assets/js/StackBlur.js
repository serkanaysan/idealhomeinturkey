function stackBlurImage(a,t,e,r,n,l){var c=a,g=t.width,o=t.height,e=e,s=e.getContext("2d");s.clearRect(0,0,g,o),s.drawImage(c,0,0,g,o),isNaN(r)||1>r||(n?stackBlurCanvasRGBA(e,0,0,g,o,r,l):stackBlurCanvasRGB(e,0,0,g,o,r,l))}function stackBlurCanvasRGBA(a,t,e,r,n,l,c){if(!(isNaN(l)||1>l)){l|=0;var g,o=a,s=o.getContext("2d");try{try{g=s.getImageData(t,e,r,n)}catch(i){try{netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead"),g=s.getImageData(t,e,r,n)}catch(i){throw alert("Cannot access local image"),new Error("unable to access local image data: "+i)}}}catch(i){throw alert("Cannot access image"),new Error("unable to access image data: "+i)}var b,u,f,x,v,h,m,w,B,d,C,k,y,I,R,D,N,_,S,p,E,G,P,A,M=g.data,U=l+l+1,j=r-1,q=n-1,z=l+1,F=z*(z+1)/2,H=new BlurStack,J=H;for(f=1;U>f;f++)if(J=J.next=new BlurStack,f==z)var K=J;J.next=H;var L=null,O=null;m=h=0;var Q=mul_table[l],T=shg_table[l];for(u=0;n>u;u++){for(D=N=_=S=w=B=d=C=0,k=z*(p=M[h]),y=z*(E=M[h+1]),I=z*(G=M[h+2]),R=z*(P=M[h+3]),w+=F*p,B+=F*E,d+=F*G,C+=F*P,J=H,f=0;z>f;f++)J.r=p,J.g=E,J.b=G,J.a=P,J=J.next;for(f=1;z>f;f++)x=h+((f>j?j:f)<<2),w+=(J.r=p=M[x])*(A=z-f),B+=(J.g=E=M[x+1])*A,d+=(J.b=G=M[x+2])*A,C+=(J.a=P=M[x+3])*A,D+=p,N+=E,_+=G,S+=P,J=J.next;for(L=H,O=K,b=0;r>b;b++)M[h+3]=P=C*Q>>T,0!=P?(P=255/P,M[h]=(w*Q>>T)*P,M[h+1]=(B*Q>>T)*P,M[h+2]=(d*Q>>T)*P):M[h]=M[h+1]=M[h+2]=0,w-=k,B-=y,d-=I,C-=R,k-=L.r,y-=L.g,I-=L.b,R-=L.a,x=m+((x=b+l+1)<j?x:j)<<2,D+=L.r=M[x],N+=L.g=M[x+1],_+=L.b=M[x+2],S+=L.a=M[x+3],w+=D,B+=N,d+=_,C+=S,L=L.next,k+=p=O.r,y+=E=O.g,I+=G=O.b,R+=P=O.a,D-=p,N-=E,_-=G,S-=P,O=O.next,h+=4;m+=r}for(b=0;r>b;b++){for(N=_=S=D=B=d=C=w=0,h=b<<2,k=z*(p=M[h]),y=z*(E=M[h+1]),I=z*(G=M[h+2]),R=z*(P=M[h+3]),w+=F*p,B+=F*E,d+=F*G,C+=F*P,J=H,f=0;z>f;f++)J.r=p,J.g=E,J.b=G,J.a=P,J=J.next;for(v=r,f=1;l>=f;f++)h=v+b<<2,w+=(J.r=p=M[h])*(A=z-f),B+=(J.g=E=M[h+1])*A,d+=(J.b=G=M[h+2])*A,C+=(J.a=P=M[h+3])*A,D+=p,N+=E,_+=G,S+=P,J=J.next,q>f&&(v+=r);for(h=b,L=H,O=K,u=0;n>u;u++)x=h<<2,M[x+3]=P=C*Q>>T,P>0?(P=255/P,M[x]=(w*Q>>T)*P,M[x+1]=(B*Q>>T)*P,M[x+2]=(d*Q>>T)*P):M[x]=M[x+1]=M[x+2]=0,w-=k,B-=y,d-=I,C-=R,k-=L.r,y-=L.g,I-=L.b,R-=L.a,x=b+((x=u+z)<q?x:q)*r<<2,w+=D+=L.r=M[x],B+=N+=L.g=M[x+1],d+=_+=L.b=M[x+2],C+=S+=L.a=M[x+3],L=L.next,k+=p=O.r,y+=E=O.g,I+=G=O.b,R+=P=O.a,D-=p,N-=E,_-=G,S-=P,O=O.next,h+=r}s.putImageData(g,t,e),c.call()}}function stackBlurCanvasRGB(a,t,e,r,n,l,c){if(!(isNaN(l)||1>l)){l|=0;var g,o=a,s=o.getContext("2d");try{try{g=s.getImageData(t,e,r,n)}catch(i){try{netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead"),g=s.getImageData(t,e,r,n)}catch(i){throw alert("Cannot access local image"),new Error("unable to access local image data: "+i)}}}catch(i){throw alert("Cannot access image"),new Error("unable to access image data: "+i)}var b,u,f,x,v,h,m,w,B,d,C,k,y,I,R,D,N,_,S,p,E=g.data,G=l+l+1,P=r-1,A=n-1,M=l+1,U=M*(M+1)/2,j=new BlurStack,q=j;for(f=1;G>f;f++)if(q=q.next=new BlurStack,f==M)var z=q;q.next=j;var F=null,H=null;m=h=0;var J=mul_table[l],K=shg_table[l];for(u=0;n>u;u++){for(I=R=D=w=B=d=0,C=M*(N=E[h]),k=M*(_=E[h+1]),y=M*(S=E[h+2]),w+=U*N,B+=U*_,d+=U*S,q=j,f=0;M>f;f++)q.r=N,q.g=_,q.b=S,q=q.next;for(f=1;M>f;f++)x=h+((f>P?P:f)<<2),w+=(q.r=N=E[x])*(p=M-f),B+=(q.g=_=E[x+1])*p,d+=(q.b=S=E[x+2])*p,I+=N,R+=_,D+=S,q=q.next;for(F=j,H=z,b=0;r>b;b++)E[h]=w*J>>K,E[h+1]=B*J>>K,E[h+2]=d*J>>K,w-=C,B-=k,d-=y,C-=F.r,k-=F.g,y-=F.b,x=m+((x=b+l+1)<P?x:P)<<2,I+=F.r=E[x],R+=F.g=E[x+1],D+=F.b=E[x+2],w+=I,B+=R,d+=D,F=F.next,C+=N=H.r,k+=_=H.g,y+=S=H.b,I-=N,R-=_,D-=S,H=H.next,h+=4;m+=r}for(b=0;r>b;b++){for(R=D=I=B=d=w=0,h=b<<2,C=M*(N=E[h]),k=M*(_=E[h+1]),y=M*(S=E[h+2]),w+=U*N,B+=U*_,d+=U*S,q=j,f=0;M>f;f++)q.r=N,q.g=_,q.b=S,q=q.next;for(v=r,f=1;l>=f;f++)h=v+b<<2,w+=(q.r=N=E[h])*(p=M-f),B+=(q.g=_=E[h+1])*p,d+=(q.b=S=E[h+2])*p,I+=N,R+=_,D+=S,q=q.next,A>f&&(v+=r);for(h=b,F=j,H=z,u=0;n>u;u++)x=h<<2,E[x]=w*J>>K,E[x+1]=B*J>>K,E[x+2]=d*J>>K,w-=C,B-=k,d-=y,C-=F.r,k-=F.g,y-=F.b,x=b+((x=u+M)<A?x:A)*r<<2,w+=I+=F.r=E[x],B+=R+=F.g=E[x+1],d+=D+=F.b=E[x+2],F=F.next,C+=N=H.r,k+=_=H.g,y+=S=H.b,I-=N,R-=_,D-=S,H=H.next,h+=r}s.putImageData(g,t,e),c.call()}}function BlurStack(){this.r=0,this.g=0,this.b=0,this.a=0,this.next=null}var mul_table=[512,512,456,512,328,456,335,512,405,328,271,456,388,335,292,512,454,405,364,328,298,271,496,456,420,388,360,335,312,292,273,512,482,454,428,405,383,364,345,328,312,298,284,271,259,496,475,456,437,420,404,388,374,360,347,335,323,312,302,292,282,273,265,512,497,482,468,454,441,428,417,405,394,383,373,364,354,345,337,328,320,312,305,298,291,284,278,271,265,259,507,496,485,475,465,456,446,437,428,420,412,404,396,388,381,374,367,360,354,347,341,335,329,323,318,312,307,302,297,292,287,282,278,273,269,265,261,512,505,497,489,482,475,468,461,454,447,441,435,428,422,417,411,405,399,394,389,383,378,373,368,364,359,354,350,345,341,337,332,328,324,320,316,312,309,305,301,298,294,291,287,284,281,278,274,271,268,265,262,259,257,507,501,496,491,485,480,475,470,465,460,456,451,446,442,437,433,428,424,420,416,412,408,404,400,396,392,388,385,381,377,374,370,367,363,360,357,354,350,347,344,341,338,335,332,329,326,323,320,318,315,312,310,307,304,302,299,297,294,292,289,287,285,282,280,278,275,273,271,269,267,265,263,261,259],shg_table=[9,11,12,13,13,14,14,15,15,15,15,16,16,16,16,17,17,17,17,17,17,17,18,18,18,18,18,18,18,18,18,19,19,19,19,19,19,19,19,19,19,19,19,19,19,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,21,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,22,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,23,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24,24];