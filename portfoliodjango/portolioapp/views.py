from django.shortcuts import render, redirect
from django.contrib import messages
from django.core.mail import send_mail
from django.conf import settings
from .forms import ContactForm

def contact(request):
    if request.method == 'POST':
        form = ContactForm(request.POST)

        if form.is_valid():
            contact = form.save()  # save to database

            send_mail(
                subject=f"New contact: {contact.subject}",
                message=f"Message from {contact.name} ({contact.email}):\n\n{contact.message}\n\nPhone: {contact.phone}",
                from_email=settings.EMAIL_HOST_USER,
                recipient_list=[settings.EMAIL_HOST_USER],
                fail_silently=False,
            )

           

    else:
        form = ContactForm()

    return render(request, 'contact.html', {'form': form})

